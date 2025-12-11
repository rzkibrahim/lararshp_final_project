@extends('layouts.lte.app')

@section('title', 'Buat Rekam Medis - Admin')
@section('page', 'Buat Rekam Medis Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-file-medical-alt mr-2 text-blue-600"></i> Buat Rekam Medis Baru
            </h2>
            <p class="text-gray-600">Isi form berikut untuk membuat rekam medis baru</p>
        </div>

        {{-- Info Reservasi --}}
        <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">ID Reservasi</span>
                    <p class="font-semibold text-lg">#{{ $info->idreservasi_dokter }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">No. Urut</span>
                    <p class="font-semibold text-lg">{{ $info->no_urut }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Waktu Daftar</span>
                    <p class="font-semibold">{{ $info->waktu_daftar }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Pet</span>
                    <p class="font-semibold">{{ $info->nama_pet }}</p>
                </div>
                <div class="md:col-span-2">
                    <span class="text-sm text-gray-500">Pemilik</span>
                    <p class="font-semibold">{{ $info->nama_pemilik }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.rekam-medis.store') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="idreservasi" value="{{ $info->idreservasi_dokter }}">
            <input type="hidden" name="idpet" value="{{ $info->idpet }}">

            {{-- Dokter Pemeriksa --}}
            <div>
                <label for="dokter_pemeriksa" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-md mr-1 text-blue-600"></i> Dokter Pemeriksa *
                </label>
                <select id="dokter_pemeriksa" name="dokter_pemeriksa" required
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dokter_pemeriksa') border-red-500 @enderror">
                    <option value="">— pilih dokter —</option>
                    @foreach($listDokter as $d)
                    <option value="{{ $d->idrole_user }}" {{ old('dokter_pemeriksa') == $d->idrole_user ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                    @endforeach
                </select>
                @error('dokter_pemeriksa')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Anamnesa --}}
            <div>
                <label for="anamnesa" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-stethoscope mr-1 text-blue-600"></i> Anamnesa
                </label>
                <textarea id="anamnesa" name="anamnesa" rows="3"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('anamnesa') border-red-500 @enderror"
                    placeholder="Masukkan anamnesa...">{{ old('anamnesa') }}</textarea>
                @error('anamnesa')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Temuan Klinis --}}
            <div>
                <label for="temuan_klinis" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-clipboard-check mr-1 text-blue-600"></i> Temuan Klinis
                </label>
                <textarea id="temuan_klinis" name="temuan_klinis" rows="3"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('temuan_klinis') border-red-500 @enderror"
                    placeholder="Masukkan temuan klinis...">{{ old('temuan_klinis') }}</textarea>
                @error('temuan_klinis')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Diagnosa --}}
            <div>
                <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-diagnoses mr-1 text-blue-600"></i> Diagnosa
                </label>
                <textarea id="diagnosa" name="diagnosa" rows="3"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('diagnosa') border-red-500 @enderror"
                    placeholder="Masukkan diagnosa...">{{ old('diagnosa') }}</textarea>
                @error('diagnosa')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Note --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Informasi:</strong> Setelah menyimpan data rekam medis, Anda akan diarahkan ke halaman detail untuk menambahkan tindakan terapi.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.rekam-medis.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-save mr-1"></i> Simpan & Lanjut ke Detail
                </button>
            </div>
        </form>
    </div>
</div>
@endsection