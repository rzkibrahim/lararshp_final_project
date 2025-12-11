@extends('layouts.lte.app')

@section('title', 'Sampah Temu Dokter - Admin')
@section('page', 'Sampah Temu Dokter')

@section('content')
<div class="space-y-6">
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-trash-alt mr-2 text-red-600"></i>
                    Sampah Temu Dokter
                </h2>
                <p class="text-gray-600 mt-1">Data temu dokter yang telah dihapus</p>
            </div>
            <a href="{{ route('admin.temu-dokter.index') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- Trash Table --}}
    <div class="bg-white rounded-xl shadow-lg p-6">
        @if($trashedItems->count() === 0)
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-trash text-5xl mb-3 text-gray-300"></i>
                <p class="text-lg">Sampah kosong</p>
                <p class="text-sm">Tidak ada temu dokter yang dihapus</p>
            </div>
        @else
            <div class="mb-4">
                <p class="text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    Total: <strong>{{ $trashedItems->count() }}</strong> item di sampah
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">No. Urut</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Waktu Daftar</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pet</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pemilik</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dokter</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dihapus Oleh</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Waktu Hapus</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($trashedItems as $item)
                        @php
                            $status_config = [
                                0 => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Menunggu'],
                                1 => ['class' => 'bg-green-100 text-green-800', 'text' => 'Selesai'],
                                2 => ['class' => 'bg-red-100 text-red-800', 'text' => 'Batal']
                            ];
                            $status = $status_config[$item->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Unknown'];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm">#{{ $item->idreservasi_dokter }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">{{ $item->no_urut }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ \Carbon\Carbon::parse($item->waktu_daftar)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_pet }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_pemilik }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_dokter }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $status['class'] }}">
                                    {{ $status['text'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $item->deleted_by_nama ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    {{-- Restore --}}
                                    <form method="POST" action="{{ route('admin.temu-dokter.restore', $item->idreservasi_dokter) }}" 
                                          class="inline"
                                          onsubmit="return confirm('Pulihkan temu dokter ini?')">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                                title="Pulihkan">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>

                                    {{-- Force Delete --}}
                                    <form method="POST" action="{{ route('admin.temu-dokter.force-delete', $item->idreservasi_dokter) }}" 
                                          class="inline"
                                          onsubmit="return confirm('PERHATIAN! Data akan dihapus PERMANEN dan tidak dapat dipulihkan. Lanjutkan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                                title="Hapus Permanen">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Info Box --}}
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Informasi Penting:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><strong>Pulihkan:</strong> Mengembalikan data ke daftar utama</li>
                            <li><strong>Hapus Permanen:</strong> Menghapus data secara permanen dari database (TIDAK DAPAT DIKEMBALIKAN)</li>
                            <li>Data yang dihapus permanen juga akan menghapus semua rekam medis terkait</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection