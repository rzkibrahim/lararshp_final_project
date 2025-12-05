{{-- ============================================================ --}}
{{-- 4. ras-hewan/trash.blade.php --}}
{{-- ============================================================ --}}

@extends('layouts.lte.app')

@section('title', 'Trash - Ras Hewan')
@section('page', 'Trash Ras Hewan')

@section('content')
<div class="max-w-7xl mx-auto">

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Perhatian:</strong> Data di trash dapat dikembalikan atau dihapus permanen.
                    Penghapusan permanen tidak dapat dibatalkan!
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-700 to-red-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-trash-restore mr-2"></i>
                        Trash - Ras Hewan
                    </h2>
                    <p class="text-red-100 text-sm mt-1">Data ras hewan yang telah dihapus sementara</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-red-100">Total: {{ $rasHewan->count() }} data</span>
                    <a href="{{ route('admin.ras-hewan.index') }}"
                        class="bg-white text-red-700 px-4 py-2 rounded-lg hover:bg-red-50 transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-4 text-left font-semibold text-gray-700">No</th>
                        <th class="p-4 text-left font-semibold text-gray-700">ID Ras</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Nama Ras</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Jenis Hewan</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Dihapus Pada</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Dihapus Oleh</th>
                        <th class="p-4 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rasHewan as $index => $item)
                    <tr class="border-b border-gray-200 hover:bg-red-50 transition duration-150">
                        <td class="p-4 text-gray-600">{{ $index + 1 }}</td>
                        <td class="p-4 text-gray-800 font-medium">{{ $item->idras_hewan }}</td>
                        <td class="p-4 text-gray-800">{{ $item->nama_ras }}</td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $item->nama_jenis_hewan ?? '-' }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-600">
                            <div class="flex flex-col">
                                <span class="text-sm">{{ \Carbon\Carbon::parse($item->deleted_at)->format('d M Y') }}</span>
                                <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->deleted_at)->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-user mr-1"></i>{{ $item->deleted_by_name ?? 'System' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2">
                                <form action="{{ route('admin.ras-hewan.restore', $item->idras_hewan) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan ras hewan ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-200">
                                        <i class="fas fa-undo mr-1"></i>Restore
                                    </button>
                                </form>

                                <form action="{{ route('admin.ras-hewan.force-delete', $item->idras_hewan) }}"
                                    method="POST"
                                    class="force-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 text-sm text-white bg-red-700 rounded-lg hover:bg-red-800 transition duration-200">
                                        <i class="fas fa-trash-alt mr-1"></i>Hapus Permanen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500">
                            <i class="fas fa-trash text-4xl mb-4 text-gray-300"></i>
                            <p class="text-lg">Trash kosong</p>
                            <p class="text-sm text-gray-400 mt-2">Tidak ada data ras hewan yang dihapus</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rasHewan->count() > 0)
        <div class="p-4 bg-gray-50 border-t border-gray-200 text-sm text-gray-700">
            Menampilkan <span class="font-medium">{{ $rasHewan->count() }}</span> data di trash
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hardDeleteForms = document.querySelectorAll('.force-delete-form');
        hardDeleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('⚠️ PERINGATAN!\n\nData akan DIHAPUS PERMANEN dari database!\nTindakan ini TIDAK DAPAT DIBATALKAN!\n\nApakah Anda yakin?')) {
                    const userInput = prompt('Ketik "HAPUS" (tanpa tanda kutip):');
                    if (userInput === 'HAPUS') {
                        this.submit();
                    } else {
                        alert('Penghapusan dibatalkan');
                    }
                }
            });
        });
    });
</script>
@endsection