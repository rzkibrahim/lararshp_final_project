<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = DB::table('kategori')
            ->leftJoin('kode_tindakan_terapi', function ($join) {
                $join->on('kategori.idkategori', '=', 'kode_tindakan_terapi.idkategori')
                    ->whereNull('kode_tindakan_terapi.deleted_at');
            })
            ->select(
                'kategori.idkategori',
                'kategori.nama_kategori',
                DB::raw('COUNT(kode_tindakan_terapi.idkode_tindakan_terapi) AS jumlah_tindakan')
            )
            ->whereNull('kategori.deleted_at')
            ->groupBy('kategori.idkategori', 'kategori.nama_kategori')
            ->orderBy('kategori.idkategori', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('rshp.admin.DataMaster.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,NULL,idkategori,deleted_at,NULL',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);

        DB::table('kategori')->insert([
            'nama_kategori' => $request->nama_kategori,
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = DB::table('kategori')
            ->where('idkategori', $id)
            ->whereNull('deleted_at')
            ->first();

        if (!$kategori) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori,' . $id . ',idkategori,deleted_at,NULL',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
        ]);

        DB::table('kategori')
            ->where('idkategori', $id)
            ->update([
                'nama_kategori' => $request->nama_kategori
            ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function trash()
    {
        $kategori = DB::table('kategori')
            ->leftJoin('user', 'kategori.deleted_by', '=', 'user.iduser')
            ->select(
                'kategori.*',
                'user.nama as deleted_by_name'
            )
            ->whereNotNull('kategori.deleted_at')
            ->orderBy('kategori.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.kategori.trash', compact('kategori'));
    }

    public function destroy($id)
    {
        $digunakan = DB::table('kode_tindakan_terapi')
            ->where('idkategori', $id)
            ->whereNull('deleted_at')
            ->count();

        if ($digunakan > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan.');
        }

        DB::table('kategori')
            ->where('idkategori', $id)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id()
            ]);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dipindahkan ke trash.');
    }

    public function restore($id)
    {
        DB::table('kategori')
            ->where('idkategori', $id)
            ->update([
                'deleted_at' => null,
                'deleted_by' => null
            ]);

        return redirect()->route('admin.kategori.trash')
            ->with('success', 'Kategori berhasil dikembalikan!');
    }

    public function forceDelete($id)
    {
        $digunakan = DB::table('kode_tindakan_terapi')
            ->where('idkategori', $id)
            ->exists();

        if ($digunakan) {
            return redirect()->route('admin.kategori.trash')
                ->with('error', 'Tidak dapat menghapus permanen karena masih ada data tindakan terkait!');
        }

        DB::table('kategori')->where('idkategori', $id)->delete();

        return redirect()->route('admin.kategori.trash')
            ->with('success', 'Kategori berhasil dihapus permanen!');
    }
}
