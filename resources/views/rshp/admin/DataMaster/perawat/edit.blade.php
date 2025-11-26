@extends('layouts.lte.app')

@section('title', 'Edit Perawat')
@section('page', 'Edit Perawat')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-green-700 to-green-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Edit Data Perawat</h2>
            <p class="text-green-100 text-sm mt-1">Perbarui informasi perawat</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.perawat.update', $perawat->idrole_user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="mb-6">
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Perawat <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" id="nama"
                       value="{{ old('nama', $perawat->nama) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama perawat"
                       required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email"
                       value="{{ old('email', $perawat->email) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('email') border-red-500 @enderror"
                       placeholder="perawat@example.com"
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 @error('status') border-red-500 @enderror"
                        required>
                    <option value="1" {{ old('status', $perawat->status) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $perawat->status) == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info User ID --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <i class="fas fa-info-circle text-gray-400"></i>
                    <span>ID User: <strong>{{ $perawat->iduser }}</strong></span>
                    <span class="mx-2">|</span>
                    <span>ID Role User: <strong>{{ $perawat->idrole_user }}</strong></span>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.perawat.index') }}"
                   class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Perbarui
                </button>
            </div>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-0.5"></i>
            <div>
                <h4 class="font-semibold text-yellow-900 mb-1">Perhatian</h4>
                <ul class="text-yellow-800 text-sm space-y-1">
                    <li>• Perubahan nama dan email akan mempengaruhi akses login perawat</li>
                    <li>• Status nonaktif akan menonaktifkan akses perawat ke sistem</li>
                    <li>• User ID tidak dapat diubah setelah data dibuat</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection