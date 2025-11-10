@extends('layouts.lte.app')

@section('title', 'Edit Data Pet')
@section('page', 'Pet')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <h2 class="text-2xl font-semibold text-blue-600 mb-6 flex items-center">
        <i class="fas fa-dog mr-2 text-blue-500"></i> Edit Data Pet: {{ $pet->nama}}
    </h2>

    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6 flex items-start">
        <i class="fas fa-info-circle mt-1 mr-2"></i>
        <p class="text-sm">Pastikan data pet akurat, terutama jenis hewan dan pemiliknya.</p>
    </div>

    <form action="{{ route('admin.pet.update', $pet->idpet) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Pet</label>
                <input type="text" id="nama" name="nama"
                    value="{{ old('nama', $pet->nama) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="M" {{ $pet->jenis_kelamin == 'M' ? 'selected' : '' }}>Jantan</option>
                    <option value="F" {{ $pet->jenis_kelamin == 'F' ? 'selected' : '' }}>Betina</option>
                </select>
            </div>

            <div>
                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                    value="{{ old('tanggal_lahir', $pet->tanggal_lahir) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="warna_tanda" class="block text-sm font-medium text-gray-700 mb-2">Warna / Tanda Khusus</label>
                <input type="text" id="warna_tanda" name="warna_tanda"
                    value="{{ old('warna_tanda', $pet->warna_tanda) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="idras_hewan" class="block text-sm font-medium text-gray-700 mb-2">Ras Hewan</label>
                <select id="idras_hewan" name="idras_hewan" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Ras Hewan --</option>
                    @foreach($rasHewan as $ras)
                    <option value="{{ $ras->idras_hewan }}" {{ $pet->idras_hewan == $ras->idras_hewan ? 'selected' : '' }}>
                        {{ $ras->nama_ras }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="idpemilik" class="block text-sm font-medium text-gray-700 mb-2">Pemilik</label>
                <select id="idpemilik" name="idpemilik" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Pemilik --</option>
                    @foreach($pemilik as $p)
                    <option value="{{ $p->idpemilik }}" {{ $pet->idpemilik == $p->idpemilik ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.pet.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-save mr-2"></i> Update
            </button>
        </div>
    </form>
</div>
@endsection