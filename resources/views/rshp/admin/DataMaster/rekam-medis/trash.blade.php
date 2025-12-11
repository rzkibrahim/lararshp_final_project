@extends('layouts.lte.app')

@section('title', 'Sampah Rekam Medis - Admin')
@section('page', 'Sampah Rekam Medis')

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
                    Sampah Rekam Medis
                </h2>
                <p class="text-gray-600 mt-1">Data rekam medis yang telah dihapus</p>
            </div>
            <a href="{{ route('admin.rekam-medis.index') }}" 
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
                <p class="text-sm">Tidak ada rekam medis yang dihapus</p>
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
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID RM</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dibuat</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pet</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Pemilik</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dokter</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Anamnesa</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Diagnosa</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Dihapus Oleh</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Waktu Hapus</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($trashedItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-semibold">#{{ $item->idrekam_medis }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_pet }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_pemilik }}</td>
                            <td class="px-4 py-3 text-sm">{{ $item->nama_dokter }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="max-w-xs truncate" title="{{ $item->anamnesa }}">
                                    {{ Str::limit($item->anamnesa, 40) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="max-w-xs truncate" title="{{ $item->diagnosa }}">
                                    {{ Str::limit($item->diagnosa, 40) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $item->deleted_by_nama ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-sm">
                                {{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-2">
                                    {{-- Restore --}}
                                    <form method="POST" action="{{ route('admin.rekam-medis.restore', $item->idrekam_medis) }}" 
                                          class="inline"
                                          onsubmit="return confirm('Pulihkan rekam medis ini?')">
                                        @csrf
                                        <button type="submit" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                                title="Pulihkan">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>

                                    {{-- Force Delete --}}
                                    <form method="POST" action="{{ route('admin.rekam-medis.force-delete', $item->idrekam_medis) }}" 
                                          class="inline"
                                          onsubmit="return confirm('PERHATIAN! Data akan dihapus PERMANEN dan tidak dapat dipulihkan. Semua detail tindakan juga akan terhapus. Lanjutkan?')">
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
                            <li><strong>Pulihkan:</strong> Mengembalikan data rekam medis ke daftar utama</li>
                            <li><strong>Hapus Permanen:</strong> Menghapus data secara permanen dari database (TIDAK DAPAT DIKEMBALIKAN)</li>
                            <li>Data yang dihapus permanen juga akan menghapus semua detail tindakan/terapi terkait</li>
                            <li>Pastikan Anda benar-benar yakin sebelum melakukan penghapusan permanen</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection