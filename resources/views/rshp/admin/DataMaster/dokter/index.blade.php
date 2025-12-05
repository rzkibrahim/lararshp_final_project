@extends('layouts.lte.app')

@section('title', 'Data Dokter')
@section('page', 'Dokter')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Data Dokter</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-blue-100">Total: {{ $dokters->count() }} dokter</span>
                    
                    {{-- ✅ TOMBOL TRASH --}}
                    <a href="{{ route('admin.dokter.trash') }}"
                       class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Trash
                        @php
                            $trashCount = DB::table('role_user')->where('idrole', 3)->whereNotNull('deleted_at')->count();
                        @endphp
                        @if($trashCount > 0)
                            <span class="ml-1 bg-white text-red-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                {{ $trashCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.dokter.create') }}"
                       class="bg-white text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-50 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Data
                    </a>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-4 text-left font-semibold text-gray-700">No</th>
                        <th class="p-4 text-left font-semibold text-gray-700">ID Role User</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Nama</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Email</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Role</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Status</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dokters as $index => $dokter)
                        <tr class="border-b border-gray-200 hover:bg-blue-50 transition duration-150">
                            <td class="p-4 text-gray-600">{{ $index + 1 }}</td>
                            <td class="p-4 text-gray-800 font-medium">{{ $dokter->idrole_user }}</td>
                            <td class="p-4 text-gray-800">{{ $dokter->nama ?? '-' }}</td>
                            <td class="p-4 text-gray-800">{{ $dokter->email ?? '-' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-user-md mr-1"></i>{{ $dokter->nama_role }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($dokter->status == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    {{-- Cek apakah dokter masih punya rekam medis --}}
                                    @php
                                        $punyaRekamMedis = DB::table('rekam_medis')->where('dokter_pemeriksa', $dokter->idrole_user)->exists();
                                    @endphp

                                    <a href="{{ route('admin.dokter.edit', $dokter->idrole_user) }}"
                                       class="px-3 py-1 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-200">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>

                                    {{-- ✅ SOFT DELETE - Pindah ke Trash --}}
                                    @if(!$punyaRekamMedis)
                                        <form action="{{ route('admin.dokter.destroy', $dokter->idrole_user) }}"
                                              method="POST"
                                              onsubmit="return confirm('Data akan dipindahkan ke trash. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-sm text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition duration-200">
                                                <i class="fas fa-trash mr-1"></i>Hapus
                                            </button>
                                        </form>
                                    @else
                                        <button disabled
                                                class="px-3 py-1 text-sm text-gray-400 bg-gray-200 rounded-lg cursor-not-allowed"
                                                title="Dokter ini memiliki rekam medis, tidak bisa dihapus">
                                            <i class="fas fa-lock mr-1"></i>Terkunci
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                <i class="fas fa-user-md text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">Tidak ada data dokter</p>
                                <a href="{{ route('admin.dokter.create') }}"
                                   class="inline-block mt-2 text-blue-600 hover:text-blue-800">
                                    Tambah data pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dokters->count() > 0)
        <div class="p-4 bg-gray-50 border-t border-gray-200 text-sm text-gray-700 flex justify-between items-center">
            <span>Menampilkan <span class="font-medium">{{ $dokters->count() }}</span> data aktif</span>
            <a href="{{ route('admin.dokter.trash') }}" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash mr-1"></i>Lihat Trash
            </a>
        </div>
        @endif
    </div>
</div>
@endsection