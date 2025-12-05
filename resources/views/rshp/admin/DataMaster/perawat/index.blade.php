@extends('layouts.lte.app')

@section('title', 'Data Perawat')
@section('page', 'Perawat')

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
        <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Data Perawat</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-green-100">Total: {{ $perawats->count() }} perawat</span>
                    
                    {{-- ✅ TOMBOL TRASH --}}
                    <a href="{{ route('admin.perawat.trash') }}"
                       class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Trash
                        @php
                            $trashCount = DB::table('role_user')->where('idrole', 4)->whereNotNull('deleted_at')->count();
                        @endphp
                        @if($trashCount > 0)
                            <span class="ml-1 bg-white text-red-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                {{ $trashCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.perawat.create') }}"
                       class="bg-white text-green-700 px-4 py-2 rounded-lg hover:bg-green-50 transition duration-200">
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
                    @forelse ($perawats as $index => $perawat)
                        <tr class="border-b border-gray-200 hover:bg-green-50 transition duration-150">
                            <td class="p-4 text-gray-600">{{ $index + 1 }}</td>
                            <td class="p-4 text-gray-800 font-medium">{{ $perawat->idrole_user }}</td>
                            <td class="p-4 text-gray-800">{{ $perawat->nama ?? '-' }}</td>
                            <td class="p-4 text-gray-800">{{ $perawat->email ?? '-' }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-user-nurse mr-1"></i>{{ $perawat->nama_role }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($perawat->status == 1)
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
                                    {{-- Cek apakah perawat masih punya rekam medis --}}
                                    @php
                                        $punyaRekamMedis = DB::table('rekam_medis')->where('dokter_pemeriksa', $perawat->idrole_user)->exists();
                                    @endphp

                                    <a href="{{ route('admin.perawat.edit', $perawat->idrole_user) }}"
                                       class="px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-200">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>

                                    {{-- ✅ SOFT DELETE - Pindah ke Trash --}}
                                    @if(!$punyaRekamMedis)
                                        <form action="{{ route('admin.perawat.destroy', $perawat->idrole_user) }}"
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
                                                title="Perawat ini memiliki rekam medis, tidak bisa dihapus">
                                            <i class="fas fa-lock mr-1"></i>Terkunci
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                <i class="fas fa-user-nurse text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">Tidak ada data perawat</p>
                                <a href="{{ route('admin.perawat.create') }}"
                                   class="inline-block mt-2 text-green-600 hover:text-green-800">
                                    Tambah data pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($perawats->count() > 0)
        <div class="p-4 bg-gray-50 border-t border-gray-200 text-sm text-gray-700 flex justify-between items-center">
            <span>Menampilkan <span class="font-medium">{{ $perawats->count() }}</span> data aktif</span>
            <a href="{{ route('admin.perawat.trash') }}" class="text-red-600 hover:text-red-800">
                <i class="fas fa-trash mr-1"></i>Lihat Trash
            </a>
        </div>
        @endif
    </div>
</div>
@endsection