@extends('layouts.lte.app')

@section('title', 'Detail Rekam Medis - Admin')
@section('page', 'Detail Rekam Medis #' . $header->idrekam_medis)

@push('styles')
<style>
    .detail-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #0067b6;
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Alert Messages --}}
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

    {{-- Header Info --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-2xl p-6 mb-8 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div class="mb-4 md:mb-0">
                <h3 class="text-2xl font-bold mb-2">
                    <i class="fas fa-file-medical mr-2"></i> Detail Rekam Medis #{{ $header->idrekam_medis }}
                </h3>
                <p class="text-blue-100 opacity-90">Kelola detail rekam medis dan tindakan terapi</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.rekam-medis.index') }}"
                    class="bg-white text-blue-600 hover:bg-blue-50 px-5 py-2.5 rounded-lg font-semibold transition-all action-btn">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="button" onclick="window.print()"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold transition-all action-btn">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Patient Info --}}
        <div class="lg:col-span-1">
            {{-- Patient Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h5 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i> Informasi Pasien
                </h5>
                <div class="space-y-4">
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">ID Reservasi</span>
                        <p class="font-semibold text-gray-900">#{{ $header->idreservasi_dokter }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Pet</span>
                        <p class="font-semibold text-gray-900">{{ $header->nama_pet }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Pemilik</span>
                        <p class="font-semibold text-gray-900">{{ $header->nama_pemilik ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Dokter Pemeriksa</span>
                        <p class="font-semibold text-gray-900">{{ $header->nama_dokter ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500 block mb-1">Tanggal Dibuat</span>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($header->created_at)->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h5 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">
                    <i class="fas fa-bolt mr-2 text-yellow-600"></i> Aksi Cepat
                </h5>
                <div class="space-y-3">
                    <a href="{{ route('admin.rekam-medis.edit', $header->idrekam_medis) }}"
                        class="flex items-center justify-between w-full bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg transition-all action-btn">
                        <span class="font-medium">
                            <i class="fas fa-edit mr-2"></i> Edit Data
                        </span>
                        <i class="fas fa-arrow-right"></i>
                    </a>

                    <a href="{{ route('admin.rekam-medis.create', ['idReservasi' => $header->idreservasi_dokter, 'idPet' => $header->idpet]) }}"
                        class="flex items-center justify-between w-full bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg transition-all action-btn">
                        <span class="font-medium">
                            <i class="fas fa-copy mr-2"></i> Duplikat
                        </span>
                        <i class="fas fa-arrow-right"></i>
                    </a>

                    <form method="POST" action="{{ route('admin.rekam-medis.destroy', $header->idrekam_medis) }}"
                        onsubmit="return confirm('Hapus rekam medis ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex items-center justify-between w-full bg-red-50 hover:bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg transition-all action-btn">
                            <span class="font-medium">
                                <i class="fas fa-trash mr-2"></i> Hapus
                            </span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Main Content --}}
        <div class="lg:col-span-2">
            {{-- Data Pemeriksaan --}}
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-stethoscope mr-2 text-blue-600"></i> Data Pemeriksaan
                    </h2>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        Data Utama
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.rekam-medis.update-header', $header->idrekam_medis) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Anamnesa --}}
                    <div>
                        <label for="anamnesa" class="block text-sm font-medium text-gray-700 mb-2">
                            Anamnesa
                        </label>
                        <textarea id="anamnesa" name="anamnesa" rows="4"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('anamnesa') border-red-500 @enderror">{{ old('anamnesa', $header->anamnesa) }}</textarea>
                        @error('anamnesa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Temuan Klinis --}}
                    <div>
                        <label for="temuan_klinis" class="block text-sm font-medium text-gray-700 mb-2">
                            Temuan Klinis
                        </label>
                        <textarea id="temuan_klinis" name="temuan_klinis" rows="4"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('temuan_klinis') border-red-500 @enderror">{{ old('temuan_klinis', $header->temuan_klinis) }}</textarea>
                        @error('temuan_klinis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Diagnosa --}}
                    <div>
                        <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                            Diagnosa
                        </label>
                        <textarea id="diagnosa" name="diagnosa" rows="4"
                            class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('diagnosa') border-red-500 @enderror">{{ old('diagnosa', $header->diagnosa) }}</textarea>
                        @error('diagnosa')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.rekam-medis.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tindakan Terapi --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-procedures mr-2 text-green-600"></i> Tindakan Terapi
                    </h2>
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $detailTindakan->count() }} Tindakan
                    </span>
                </div>

                {{-- Form Tambah Tindakan --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-gray-800 mb-3">
                        <i class="fas fa-plus-circle mr-1 text-green-600"></i> Tambah Tindakan Baru
                    </h4>

                    <form method="POST" action="{{ route('admin.rekam-medis.create-detail', $header->idrekam_medis) }}"
                        class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Pilih Tindakan --}}
                            <div>
                                <label for="idkode_tindakan_terapi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tindakan *
                                </label>
                                <select id="idkode_tindakan_terapi" name="idkode_tindakan_terapi" required
                                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">— pilih tindakan —</option>
                                    @foreach($listKode as $kode)
                                    <option value="{{ $kode->idkode_tindakan_terapi }}">{{ $kode->label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="detail" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan (opsional)
                                </label>
                                <input type="text" id="detail" name="detail"
                                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Masukkan catatan...">
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg font-semibold transition-all">
                                    <i class="fas fa-plus mr-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tabel Tindakan --}}
                @if($detailTindakan->count() === 0)
                <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg">
                    <i class="fas fa-procedures text-4xl mb-3 text-gray-300"></i>
                    <p>Belum ada tindakan.</p>
                    <p class="text-sm text-gray-400 mt-1">Tambahkan tindakan menggunakan form di atas</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Klinis</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Catatan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-48">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($detailTindakan as $row)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                        {{ $row->kode }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $row->deskripsi_tindakan_terapi }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">
                                        {{ $row->nama_kategori }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">
                                        {{ $row->nama_kategori_klinis }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $row->detail }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex space-x-2">
                                        {{-- Form Edit --}}
                                        <form method="POST"
                                            action="{{ route('admin.rekam-medis.update-detail', ['id' => $header->idrekam_medis, 'idDetail' => $row->iddetail_rekam_medis]) }}"
                                            class="flex items-center space-x-2">
                                            @csrf
                                            @method('PUT')

                                            <select name="idkode_tindakan_terapi" required
                                                class="text-xs px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                @foreach($listKode as $kode)
                                                <option value="{{ $kode->idkode_tindakan_terapi }}"
                                                    {{ $kode->idkode_tindakan_terapi == $row->idkode_tindakan_terapi ? 'selected' : '' }}>
                                                    {{ Str::limit($kode->label, 25) }}
                                                </option>
                                                @endforeach
                                            </select>

                                            <input type="text" name="detail" value="{{ $row->detail }}"
                                                class="text-xs px-2 py-1 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500 w-32"
                                                placeholder="catatan">

                                            <button type="submit"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs transition-colors">
                                                <i class="fas fa-save mr-1"></i> Simpan
                                            </button>
                                        </form>

                                        {{-- Form Hapus --}}
                                        <form method="POST"
                                            action="{{ route('admin.rekam-medis.delete-detail', ['id' => $header->idrekam_medis, 'idDetail' => $row->iddetail_rekam_medis]) }}"
                                            onsubmit="return confirm('Hapus tindakan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors">
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

                {{-- Summary --}}
                <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Total: <span class="font-semibold">{{ $detailTindakan->count() }}</span> tindakan terapi
                        </div>
                        <div class="text-sm text-gray-600">
                            Terakhir diperbarui: {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto focus pada input tambah tindakan
    document.getElementById('idkode_tindakan_terapi')?.focus();

    // Confirm delete action dengan sweetalert style
    function confirmDelete(message) {
        return confirm(message || 'Apakah Anda yakin ingin menghapus tindakan ini?');
    }

    // Quick edit for diagnosis fields
    document.addEventListener('DOMContentLoaded', function() {
        // Auto resize textareas
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';

            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    });
</script>
@endpush