@extends('layouts.lte.app')

@section('title', 'Edit Jenis Hewan')
@section('page', 'Jenis Hewan')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-paw mr-2 text-blue-500"></i> Edit Jenis Hewan: {{ $jenisHewan->nama_jenis_hewan }}
    </h2>

    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6 flex items-start">
        <i class="fas fa-info-circle mt-1 mr-2"></i>
        <p class="text-sm">Pastikan nama jenis hewan unik dan menggambarkan kategori hewan secara umum.</p>
    </div>

    <form action="{{ route('admin.jenis-hewan.update', $jenisHewan->idjenis_hewan) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="nama_jenis_hewan" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Jenis Hewan <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_jenis_hewan" name="nama_jenis_hewan"
                   value="{{ old('nama_jenis_hewan', $jenisHewan->nama_jenis_hewan) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: Kucing, Anjing, Kelinci" required>
            @error('nama_jenis_hewan')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.jenis-hewan.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
