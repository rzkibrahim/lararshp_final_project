@extends('layouts.lte.app')

@section('title', 'Edit Role')
@section('page', 'Role')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-user-shield mr-2 text-blue-500"></i> Edit Role: {{ $role->nama_role }}
    </h2>

    {{-- Info Alert --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6 flex items-start">
        <i class="fas fa-info-circle mt-1 mr-2"></i>
        <p class="text-sm">
            Ubah nama role dengan hati-hati. Pastikan nama yang digunakan bersifat deskriptif
            dan sesuai dengan hak akses pengguna.
        </p>
    </div>

    {{-- Form Edit Role --}}
    <form action="{{ route('admin.role.update', $role->idrole) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nama Role --}}
        <div>
            <label for="nama_role" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Role <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_role" name="nama_role"
                value="{{ old('nama_role', $role->nama_role) }}"
                class="w-full px-4 py-2 border @error('nama_role') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Masukkan nama role (contoh: Administrator, Kasir, User)" required>
            @error('nama_role')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.role.index') }}"
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