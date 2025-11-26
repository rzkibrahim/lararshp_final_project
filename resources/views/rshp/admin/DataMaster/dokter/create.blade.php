@extends('layouts.lte.app')

@section('title', 'Tambah Dokter')
@section('page', 'Tambah Dokter')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-6 py-4">
            <h2 class="text-xl font-semibold text-white">Tambah Data Dokter</h2>
            <p class="text-blue-100 text-sm mt-1">Isi form berikut untuk menambahkan dokter baru</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.dokter.store') }}" method="POST" class="p-6">
            @csrf

            {{-- Pilih User --}}
            <div class="mb-6">
                <label for="iduser" class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih User <span class="text-red-500">*</span>
                </label>
                <select name="iduser" id="iduser"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('iduser') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->iduser }}" {{ old('iduser') == $user->iduser ? 'selected' : '' }}>
                            {{ $user->nama }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('iduser')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Pilih user yang akan dijadikan dokter</p>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 @error('status') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Status --</option>
                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.dokter.index') }}"
                   class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-blue-600 text-xl mt-0.5"></i>
            <div>
                <h4 class="font-semibold text-blue-900 mb-1">Informasi</h4>
                <ul class="text-blue-800 text-sm space-y-1">
                    <li>• Pilih user yang akan diberi role dokter</li>
                    <li>• User yang sudah menjadi dokter tidak akan muncul di daftar</li>
                    <li>• Status aktif berarti dokter dapat login dan mengakses sistem</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection