@extends('layouts.lte.app')

@section('title', 'Edit Kode Tindakan Terapi')
@section('page', 'Kode Tindakan Terapi')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-syringe mr-2 text-blue-500"></i> Edit Kode Tindakan/Terapi: {{ $tindakan->kode }}
    </h2>

    <form action="{{ route('admin.kode-tindakan-terapi.update', $tindakan->idkode_tindakan_terapi) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                <input type="text" name="kode" value="{{ old('kode', $tindakan->kode) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Tindakan/Terapi</label>
                <textarea name="deskripsi_tindakan_terapi" rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('deskripsi_tindakan_terapi', $tindakan->deskripsi_tindakan_terapi) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="idkategori" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        @foreach($kategori as $k)
                            <option value="{{ $k->idkategori }}" {{ $tindakan->idkategori == $k->idkategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori Klinis</label>
                    <select name="idkategori_klinis" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        @foreach($kategoriKlinis as $kk)
                            <option value="{{ $kk->idkategori_klinis }}" {{ $tindakan->idkategori_klinis == $kk->idkategori_klinis ? 'selected' : '' }}>
                                {{ $kk->nama_kategori_klinis }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.kode-tindakan-terapi.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection
