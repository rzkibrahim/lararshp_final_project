@extends('layouts.lte.app')

@section('title', 'Temu Dokter - Admin')
@section('page', 'Temu Dokter')

@push('styles')
<style>
    .queue-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #0067b6 0%, #004080 100%);
        color: white;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 103, 182, 0.3);
    }

    .filter-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Notification Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show flex items-center mb-4 p-4 rounded-lg border border-green-200 bg-green-50" role="alert">
        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
        <div class="flex-1">
            <strong class="font-bold text-green-800">Berhasil!</strong>
            <span class="text-green-700 ml-1">{{ session('success') }}</span>
        </div>
        <button type="button" class="btn-close text-green-600 hover:text-green-800" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show flex items-center mb-4 p-4 rounded-lg border border-red-200 bg-red-50" role="alert">
        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
        <div class="flex-1">
            <strong class="font-bold text-red-800">Error!</strong>
            <span class="text-red-700 ml-1">{{ session('error') }}</span>
        </div>
        <button type="button" class="btn-close text-red-600 hover:text-red-800" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl p-6 mb-8 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h3 class="text-2xl font-bold mb-2">
                    <i class="fas fa-calendar-check mr-2"></i> Manajemen Temu Dokter
                </h3>
                <p class="text-blue-100 opacity-90">Kelola semua temu dokter dan antrian di sistem</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.temu-dokter.create') }}"
                    class="bg-white text-blue-600 hover:bg-blue-50 px-5 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-plus mr-1"></i> Tambah Baru
                </a>
                <a href="{{ route('admin.temu-dokter.trash') }}"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-trash-alt mr-1"></i> Trash
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Filter Section --}}
        <div class="lg:col-span-1">
            <div class="filter-card rounded-xl p-6 shadow-sm border border-gray-100">
                <h5 class="text-lg font-semibold mb-4 text-gray-800">
                    <i class="fas fa-filter mr-2 text-blue-600"></i> Filter
                </h5>

                <form method="GET" action="{{ route('admin.temu-dokter.index') }}">
                    <div class="space-y-4">
                        {{-- Tanggal Filter --}}
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Tanggal
                            </label>
                            <input type="date" id="date" name="date"
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="{{ $selectedDate }}">
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i> Status
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value=""
                                        {{ !request('status') ? 'checked' : '' }}
                                        class="mr-2">
                                    <span class="text-sm">Semua Status</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0"
                                        {{ request('status') == '0' ? 'checked' : '' }}
                                        class="mr-2">
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Menunggu</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1"
                                        {{ request('status') == '1' ? 'checked' : '' }}
                                        class="mr-2">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Selesai</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="2"
                                        {{ request('status') == '2' ? 'checked' : '' }}
                                        class="mr-2">
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Batal</span>
                                </label>
                            </div>
                        </div>

                        {{-- Dokter Filter --}}
                        <div>
                            <label for="doctor" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-md mr-1"></i> Dokter
                            </label>
                            <select id="doctor" name="doctor"
                                class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg">
                                <option value="">Semua Dokter</option>
                                @foreach($doctors as $doctor)
                                <option value="{{ $doctor->idrole_user }}"
                                    {{ request('doctor') == $doctor->idrole_user ? 'selected' : '' }}>
                                    Dr. {{ $doctor->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 space-y-2">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 px-4 rounded-lg font-semibold transition-all">
                                <i class="fas fa-search mr-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('admin.temu-dokter.index') }}"
                                class="block w-full bg-gray-500 hover:bg-gray-600 text-white py-2.5 px-4 rounded-lg font-semibold text-center transition-all">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Quick Stats --}}
            <div class="mt-6 bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <h5 class="text-lg font-semibold mb-4 text-gray-800">
                    <i class="fas fa-chart-bar mr-2 text-blue-600"></i> Statistik
                </h5>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Antrian</span>
                        <span class="font-bold text-blue-600">{{ $antrian->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Menunggu</span>
                        <span class="font-bold text-yellow-600">{{ $antrian->where('status', 0)->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Selesai</span>
                        <span class="font-bold text-green-600">{{ $antrian->where('status', 1)->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h5 class="text-xl font-bold mb-2 sm:mb-0">
                            <i class="fas fa-list-ol mr-2"></i>
                            Daftar Temu Dokter
                        </h5>
                        <span class="bg-white text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $antrian->count() }} Data
                        </span>
                    </div>
                </div>

                @if($antrian->count() === 0)
                <div class="text-center py-12 px-6">
                    <i class="fas fa-clipboard-list text-gray-300 text-6xl mb-4"></i>
                    <h5 class="text-gray-500 text-lg font-semibold mb-2">Tidak Ada Data</h5>
                    <p class="text-gray-400">Belum ada temu dokter yang tercatat</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pet & Pemilik</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dokter</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-60">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($antrian as $row)
                            @php
                            $status_val = (int)$row->status;
                            $status_config = [
                            0 => ['class' => 'bg-yellow-50 text-yellow-800 border-yellow-200', 'text' => 'Menunggu', 'icon' => 'clock'],
                            1 => ['class' => 'bg-green-50 text-green-800 border-green-200', 'text' => 'Selesai', 'icon' => 'check'],
                            2 => ['class' => 'bg-red-50 text-red-800 border-red-200', 'text' => 'Batal', 'icon' => 'times']
                            ];
                            $status = $status_config[$status_val];
                            $waktu = \Carbon\Carbon::parse($row->waktu_daftar);
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-4">
                                    <div class="queue-number">
                                        {{ $row->no_urut }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-500">{{ $waktu->format('d/m/Y') }}</div>
                                    <div class="font-semibold text-gray-900">{{ $waktu->format('H:i') }}</div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-paw mr-2 text-gray-400"></i>
                                        <span class="font-semibold text-gray-900">{{ $row->nama_pet }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $row->nama_pemilik ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-md mr-2 text-gray-400"></i>
                                        <span class="font-semibold text-gray-900">{{ $row->nama_dokter ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $status['class'] }}">
                                        <i class="fas fa-{{ $status['icon'] }} mr-1"></i>
                                        {{ $status['text'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.temu-dokter.edit', $row->idreservasi_dokter) }}"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg font-semibold text-sm transition-all">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>

                                        @if($status_val === 0)
                                        <form method="POST" action="{{ route('admin.temu-dokter.update-status') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="idreservasi_dokter" value="{{ $row->idreservasi_dokter }}">
                                            <input type="hidden" name="status" value="1">
                                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg font-semibold text-sm transition-all">
                                                <i class="fas fa-check mr-1"></i> Selesai
                                            </button>
                                        </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.temu-dokter.destroy', $row->idreservasi_dokter) }}"
                                            class="inline"
                                            onsubmit="return confirm('Hapus temu dokter ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg font-semibold text-sm transition-all">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto submit filter on date change
        $('#date').on('change', function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endpush