@extends('layouts.lte.app')

@section('title', 'Edit Ras Hewan')
@section('page', 'Ras Hewan')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-dna mr-2 text-blue-500"></i> Edit Ras Hewan: {{ $rasHewan->nama_ras }}
    </h2>

    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6 flex items-start">
        <i class="fas fa-info-circle mt-1 mr-2"></i>
        <p class="text-sm">Pastikan ras hewan sesuai dengan jenis hewan yang dipilih.</p>
    </div>

    <form action="{{ route('admin.ras-hewan.update', $rasHewan->idras_hewan) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="idjenis_hewan" class="block text-sm font-medium text-gray-700 mb-2">
                Jenis Hewan <span class="text-red-500">*</span>
            </label>
            <select id="idjenis_hewan" name="idjenis_hewan" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Jenis Hewan --</option>
                @foreach($jenisHewan as $jenis)
                    <option value="{{ $jenis->idjenis_hewan }}" {{ $rasHewan->idjenis_hewan == $jenis->idjenis_hewan ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis_hewan }}
                    </option>
                @endforeach
            </select>
            @error('idjenis_hewan')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nama_ras" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Ras <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_ras" name="nama_ras"
                   value="{{ old('nama_ras', $rasHewan->nama_ras) }}"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Contoh: Persia, Bulldog, Anggora" required>
            @error('nama_ras')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.ras-hewan.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
