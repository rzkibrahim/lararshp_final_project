@extends('layouts.lte.app')

@section('title', 'Edit Kategori')
@section('page', 'Kategori')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-folder-open mr-2 text-blue-500"></i> Edit Kategori: {{ $kategori->nama_kategori }}
    </h2>

    <form action="{{ route('admin.kategori.update', $kategori->idkategori) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
            <input type="text" name="nama_kategori"
                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.kategori.index') }}"
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
