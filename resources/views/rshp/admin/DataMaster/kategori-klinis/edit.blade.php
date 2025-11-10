@extends('layouts.lte.app')

@section('title', 'Edit Kategori Klinis')
@section('page', 'Kategori Klinis')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-brain mr-2 text-blue-500"></i>
        Edit Kategori Klinis: {{ $kategoriKlinis->nama_kategori_klinis }}
    </h2>

    <form action="{{ route('admin.kategori-klinis.update', $kategoriKlinis->idkategori_klinis) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nama Kategori Klinis --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori Klinis</label>
            <input type="text" name="nama_kategori_klinis"
                value="{{ old('nama_kategori_klinis', $kategoriKlinis->nama_kategori_klinis) }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            @error('nama_kategori_klinis')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Masukkan deskripsi kategori...">{{ old('deskripsi', $kategoriKlinis->deskripsi ?? '') }}</textarea>
            @error('deskripsi')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.kategori-klinis.index') }}"
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
