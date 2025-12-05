<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        $kategoriKlinis = DB::table('kategori_klinis')
            ->leftJoin('kode_tindakan_terapi', function ($join) {
                $join->on('kategori_klinis.idkategori_klinis', '=', 'kode_tindakan_terapi.idkategori_klinis')
                    ->whereNull('kode_tindakan_terapi.deleted_at');
            })
            ->select(
                'kategori_klinis.idkategori_klinis',
                'kategori_klinis.nama_kategori_klinis',
                DB::raw('COUNT(kode_tindakan_terapi.idkategori_klinis) AS jumlah_tindakan')
            )
            ->whereNull('kategori_klinis.deleted_at') // ✅ Filter aktif
            ->groupBy('kategori_klinis.idkategori_klinis', 'kategori_klinis.nama_kategori_klinis')
            ->orderBy('kategori_klinis.idkategori_klinis', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.kategori-klinis.index', compact('kategoriKlinis'));
    }

    public function create()
    {
        return view('rshp.admin.DataMaster.kategori-klinis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:50|unique:kategori_klinis,nama_kategori_klinis',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        DB::table('kategori_klinis')->insert([
            'nama_kategori_klinis' => trim(ucwords(strtolower($request->nama_kategori_klinis))),
            'deskripsi' => $request->deskripsi, // tambahkan ini
        ]);

        return redirect()->route('admin.kategori-klinis.index')
            ->with('success', 'Kategori Klinis berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategoriKlinis = DB::table('kategori_klinis')
            ->select('idkategori_klinis', 'nama_kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->first();

        if (!$kategoriKlinis) {
            abort(404, 'Data kategori klinis tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.kategori-klinis.edit', compact('kategoriKlinis'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori_klinis' => 'required|string|max:50|unique:kategori_klinis,nama_kategori_klinis,' . $id . ',idkategori_klinis',
        ]);

        $kategoriKlinis = DB::table('kategori_klinis')->where('idkategori_klinis', $id)->first();

        if (!$kategoriKlinis) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Kategori Klinis tidak ditemukan.');
        }

        DB::table('kategori_klinis')
            ->where('idkategori_klinis', $id)
            ->update([
                'nama_kategori_klinis' => trim(ucwords(strtolower($request->nama_kategori_klinis))),
            ]);

        return redirect()->route('admin.kategori-klinis.index')
            ->with('success', 'Kategori Klinis berhasil diupdate');
    }


    public function destroy($id)
    {
        $digunakan = DB::table('kode_tindakan_terapi')
            ->where('idkategori_klinis', $id)
            ->whereNull('deleted_at')
            ->count();

        if ($digunakan > 0) {
            return redirect()->route('admin.kategori-klinis.index')
                ->with('error', 'Kategori Klinis tidak dapat dihapus karena masih digunakan.');
        }

        DB::table('kategori_klinis')->where('idkategori_klinis', $id)->update([
            'deleted_at' => now(),
            'deleted_by' => Auth::id()
        ]);

        return redirect()->route('admin.kategori-klinis.index')
            ->with('success', 'Kategori Klinis berhasil dipindahkan ke trash.');
    }

    public function trash()
    {
        $kategoriKlinis = DB::table('kategori_klinis')
            ->leftJoin('user', 'kategori_klinis.deleted_by', '=', 'user.iduser')
            ->select('kategori_klinis.*', 'user.nama as deleted_by_name')
            ->whereNotNull('kategori_klinis.deleted_at')
            ->orderBy('kategori_klinis.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.kategori-klinis.trash', compact('kategoriKlinis'));
    }

    public function restore($id)
    {
        DB::table('kategori_klinis')->where('idkategori_klinis', $id)->update([
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        return redirect()->route('admin.kategori-klinis.trash')
            ->with('success', 'Kategori Klinis berhasil dikembalikan!');
    }

    public function forceDelete($id)
    {
        $digunakan = DB::table('kode_tindakan_terapi')
            ->where('idkategori_klinis', $id)
            ->exists();

        if ($digunakan) {
            return redirect()->route('admin.kategori-klinis.trash')
                ->with('error', 'Tidak dapat menghapus permanen karena masih ada data terkait!');
        }

        DB::table('kategori_klinis')->where('idkategori_klinis', $id)->delete();

        return redirect()->route('admin.kategori-klinis.trash')
            ->with('success', 'Kategori Klinis berhasil dihapus permanen!');
    }
}
