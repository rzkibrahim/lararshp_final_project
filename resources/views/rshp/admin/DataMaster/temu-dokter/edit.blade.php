@extends('layouts.lte.app')

@section('title', 'Edit Temu Dokter - Admin')
@section('page', 'Edit Temu Dokter')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-edit mr-2 text-blue-600"></i> Edit Temu Dokter #{{ $temuDokter->idreservasi_dokter }}
            </h2>
            <p class="text-gray-600">Ubah data temu dokter berikut</p>
        </div>

        {{-- Current Info --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">No. Urut</span>
                    <p class="font-semibold">{{ $temuDokter->no_urut }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Waktu Daftar</span>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($temuDokter->waktu_daftar)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Pet</span>
                    <p class="font-semibold">{{ $temuDokter->nama_pet }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Pemilik</span>
                    <p class="font-semibold">{{ $temuDokter->nama_pemilik }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.temu-dokter.update', $temuDokter->idreservasi_dokter) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tanggal Daftar --}}
                <div>
                    <label for="tanggal_daftar" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-day mr-1 text-blue-600"></i> Tanggal Daftar *
                    </label>
                    <input type="date" id="tanggal_daftar" name="tanggal_daftar" 
                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_daftar') border-red-500 @enderror"
                           value="{{ old('tanggal_daftar', date('Y-m-d', strtotime($temuDokter->waktu_daftar))) }}" required>
                    @error('tanggal_daftar')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pet --}}
                <div>
                    <label for="idpet" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-paw mr-1 text-blue-600"></i> Pilih Pet *
                    </label>
                    <select id="idpet" name="idpet" required
                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('idpet') border-red-500 @enderror">
                        <option value="">— Pilih Pet —</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->idpet }}" 
                                {{ (old('idpet', $temuDokter->idpet) == $pet->idpet) ? 'selected' : '' }}>
                                {{ $pet->nama }} ({{ $pet->nama_pemilik }})
                            </option>
                        @endforeach
                    </select>
                    @error('idpet')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dokter --}}
                <div>
                    <label for="idrole_user" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-md mr-1 text-blue-600"></i> Dokter Pemeriksa *
                    </label>
                    <select id="idrole_user" name="idrole_user" required
                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('idrole_user') border-red-500 @enderror">
                        <option value="">— Pilih Dokter —</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->idrole_user }}" 
                                {{ (old('idrole_user', $temuDokter->idrole_user) == $doctor->idrole_user) ? 'selected' : '' }}>
                                Dr. {{ $doctor->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('idrole_user')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1 text-blue-600"></i> Status *
                    </label>
                    <select id="status" name="status" required
                           class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="0" {{ old('status', $temuDokter->status) == '0' ? 'selected' : '' }}>Menunggu</option>
                        <option value="1" {{ old('status', $temuDokter->status) == '1' ? 'selected' : '' }}>Selesai</option>
                        <option value="2" {{ old('status', $temuDokter->status) == '2' ? 'selected' : '' }}>Batal</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.temu-dokter.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection