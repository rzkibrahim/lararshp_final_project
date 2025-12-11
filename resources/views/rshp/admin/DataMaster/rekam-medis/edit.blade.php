@extends('layouts.lte.app')

@section('title', 'Edit Rekam Medis - Admin')
@section('page', 'Edit Rekam Medis #' . $header->idrekam_medis)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 p-4 mb-6 rounded-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-edit mr-2 text-blue-600"></i> Edit Rekam Medis #{{ $header->idrekam_medis }}
            </h2>
            <p class="text-gray-600">Ubah data rekam medis berikut</p>
        </div>

        {{-- Current Info --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">ID Reservasi</span>
                    <p class="font-semibold">#{{ $header->idreservasi_dokter }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tanggal Dibuat</span>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($header->created_at)->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Pet</span>
                    <p class="font-semibold">{{ $header->nama_pet }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Pemilik</span>
                    <p class="font-semibold">{{ $header->nama_pemilik }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Dokter</span>
                    <p class="font-semibold">{{ $header->nama_dokter ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.rekam-medis.update-header', $header->idrekam_medis) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Dokter Pemeriksa --}}
            <div>
                <label for="dokter_pemeriksa" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-md mr-1 text-blue-600"></i> Dokter Pemeriksa
                </label>
                @php
                $listDokter = DB::table('role_user as ru')
                ->select('ru.idrole_user', 'u.nama')
                ->join('user as u', 'ru.iduser', '=', 'u.iduser')
                ->where('ru.idrole', 2)
                ->where('ru.status', 1)
                ->whereNull('ru.deleted_at')
                ->orderBy('u.nama')
                ->get();
                @endphp
                <select id="dokter_pemeriksa" name="dokter_pemeriksa"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">— pilih dokter —</option>
                    @foreach($listDokter as $d)
                    <option value="{{ $d->idrole_user }}" {{ $header->dokter_pemeriksa == $d->idrole_user ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Anamnesa --}}
            <div>
                <label for="anamnesa" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-stethoscope mr-1 text-blue-600"></i> Anamnesa
                </label>
                <textarea id="anamnesa" name="anamnesa" rows="3"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('anamnesa', $header->anamnesa) }}</textarea>
            </div>

            {{-- Temuan Klinis --}}
            <div>
                <label for="temuan_klinis" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-clipboard-check mr-1 text-blue-600"></i> Temuan Klinis
                </label>
                <textarea id="temuan_klinis" name="temuan_klinis" rows="3"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('temuan_klinis', $header->temuan_klinis) }}</textarea>
            </div>

            {{-- Diagnosa --}}
            <div>
                <label for="diagnosa" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-diagnoses mr-1 text-blue-600"></i> Diagnosa
                </label>
                <textarea id="diagnosa" name="diagnosa" rows="3"
                    class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('diagnosa', $header->diagnosa) }}</textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.rekam-medis.detail', $header->idrekam_medis) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg font-semibold transition-all">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
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