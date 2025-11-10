@extends('layouts.lte.app')

@section('title', 'Edit User')
@section('page', 'User')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">

    {{-- Header --}}
    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-user-edit mr-2 text-blue-500"></i>
        Edit User: {{ $user->nama }}
    </h2>

    {{-- Info Alert --}}
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6 flex items-start">
        <i class="fas fa-info-circle mt-1 mr-2"></i>
        <p class="text-sm">
            Biarkan kolom password kosong jika tidak ingin mengubah password.
        </p>
    </div>

    {{-- Form --}}
    <form action="{{ route('admin.user.update', $user->iduser) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Nama Lengkap --}}
        <div>
            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" id="nama_lengkap" name="nama_lengkap"
                value="{{ old('nama_lengkap', $user->nama) }}"
                class="w-full px-4 py-2 border @error('nama_lengkap') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Masukkan nama lengkap" required>
            @error('nama_lengkap')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" id="email" name="email"
                value="{{ old('email', $user->email) }}"
                class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="contoh@email.com" required>
            @error('email')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Kosongkan jika tidak ingin diubah">
                @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Ulangi password baru">
            </div>
        </div>

        {{-- Nomor Telepon --}}
        <div>
            <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">
                Nomor Telepon
            </label>
            <input type="text" id="no_telepon" name="no_telepon"
                value="{{ old('no_telepon', $user->no_telepon ?? '') }}"
                class="w-full px-4 py-2 border @error('no_telepon') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="08xxxxxxxxxx">
            @error('no_telepon')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div>
            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                Alamat
            </label>
            <textarea id="alamat" name="alamat" rows="3"
                class="w-full px-4 py-2 border @error('alamat') border-red-500 @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat ?? '') }}</textarea>
            @error('alamat')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>


        {{-- Role --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Role User
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach ($roles as $role)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="role_ids[]" value="{{ $role->idrole }}"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        {{ in_array($role->idrole, $userRoles) ? 'checked' : '' }}>
                    <span class="text-gray-700">{{ $role->nama_role }}</span>
                </label>
                @endforeach
            </div>

            @error('role_ids')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.user.index') }}"
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