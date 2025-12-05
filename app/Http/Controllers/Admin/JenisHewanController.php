<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JenisHewanController extends Controller
{
    public function index()
    {
        // Ambil data yang BELUM dihapus (deleted_at IS NULL)
        $jenisHewan = DB::table('jenis_hewan')
            ->leftJoin('ras_hewan', function($join) {
                $join->on('jenis_hewan.idjenis_hewan', '=', 'ras_hewan.idjenis_hewan')
                     ->whereNull('ras_hewan.deleted_at'); // Hitung ras yang belum dihapus
            })
            ->select(
                'jenis_hewan.idjenis_hewan',
                'jenis_hewan.nama_jenis_hewan',
                DB::raw('COUNT(ras_hewan.idras_hewan) as jumlah_ras')
            )
            ->whereNull('jenis_hewan.deleted_at') // Filter data yang belum dihapus
            ->groupBy('jenis_hewan.idjenis_hewan', 'jenis_hewan.nama_jenis_hewan')
            ->orderBy('jenis_hewan.idjenis_hewan', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.jenis-hewan.index', compact('jenisHewan'));
    }

    // ✅ HALAMAN TRASH - Menampilkan data yang sudah di-soft delete
    public function trash()
    {
        $jenisHewan = DB::table('jenis_hewan')
            ->leftJoin('user', 'jenis_hewan.deleted_by', '=', 'user.iduser')
            ->select(
                'jenis_hewan.*',
                'user.nama as deleted_by_name'
            )
            ->whereNotNull('jenis_hewan.deleted_at') // Filter data yang sudah dihapus
            ->orderBy('jenis_hewan.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.jenis-hewan.trash', compact('jenisHewan'));
    }

    public function create()
    {
        return view('rshp.admin.DataMaster.jenis-hewan.create');
    }

    public function store(Request $request)
    {
        $validateData = $this->validateJenisHewan($request);
        $this->createJenisHewan($validateData);

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', 'Jenis hewan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jenisHewan = DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->whereNull('deleted_at') // Pastikan data belum dihapus
            ->first();

        if (!$jenisHewan) {
            abort(404, 'Data tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.jenis-hewan.edit', compact('jenisHewan'));
    }

    public function update(Request $request, $id)
    {
        $this->validateJenisHewan($request, $id);

        DB::table('jenis_hewan')
            ->where('idjenis_hewan', $id)
            ->whereNull('deleted_at')
            ->update([
                'nama_jenis_hewan' => $this->formatJenisHewanName($request->nama_jenis_hewan),
            ]);

        return redirect()->route('admin.jenis-hewan.index')
            ->with('success', 'Jenis hewan berhasil diupdate.');
    }

    // ✅ SOFT DELETE - Pindahkan ke trash
    public function destroy($id)
    {
        try {
            // Cek apakah jenis hewan memiliki relasi dengan ras_hewan yang masih aktif
            $hasRasHewan = DB::table('ras_hewan')
                ->where('idjenis_hewan', $id)
                ->whereNull('deleted_at')
                ->exists();
            
            if ($hasRasHewan) {
                return redirect()->route('admin.jenis-hewan.index')
                    ->with('error', 'Jenis hewan tidak dapat dihapus karena masih memiliki data ras hewan!');
            }
            
            // Soft delete: set deleted_at dan deleted_by
            DB::table('jenis_hewan')
                ->where('idjenis_hewan', $id)
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => Auth::id()
                ]);
            
            return redirect()->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil dipindahkan ke trash!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus jenis hewan: ' . $e->getMessage());
        }
    }

    // ✅ RESTORE - Kembalikan dari trash
    public function restore($id)
    {
        try {
            DB::table('jenis_hewan')
                ->where('idjenis_hewan', $id)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);
            
            return redirect()->route('admin.jenis-hewan.trash')
                ->with('success', 'Jenis hewan berhasil dikembalikan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengembalikan jenis hewan: ' . $e->getMessage());
        }
    }

    // ✅ HARD DELETE - Hapus permanen dari database
    public function forceDelete($id)
    {
        try {
            // Cek apakah ada ras hewan (termasuk yang di-trash)
            $hasRasHewan = DB::table('ras_hewan')
                ->where('idjenis_hewan', $id)
                ->exists();
            
            if ($hasRasHewan) {
                return redirect()->route('admin.jenis-hewan.trash')
                    ->with('error', 'Tidak dapat menghapus permanen karena masih ada data ras hewan terkait!');
            }
            
            // Hard delete
            DB::table('jenis_hewan')
                ->where('idjenis_hewan', $id)
                ->delete();
            
            return redirect()->route('admin.jenis-hewan.trash')
                ->with('success', 'Jenis hewan berhasil dihapus permanen!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus permanen: ' . $e->getMessage());
        }
    }

    // ==================== HELPER METHODS ====================

    public function validateJenisHewan(Request $request, $id = null)
    {
        $uniqueRule = $id
            ? 'unique:jenis_hewan,nama_jenis_hewan,' . $id . ',idjenis_hewan,deleted_at,NULL'
            : 'unique:jenis_hewan,nama_jenis_hewan,NULL,idjenis_hewan,deleted_at,NULL';

        return $request->validate([
            'nama_jenis_hewan' => ['required', 'string', 'max:255', 'min:3', $uniqueRule],
        ], [
            'nama_jenis_hewan.required' => 'Nama jenis hewan wajib diisi.',
            'nama_jenis_hewan.unique' => 'Nama jenis hewan sudah ada.',
            'nama_jenis_hewan.max' => 'Nama jenis hewan maksimal 255 karakter.',
            'nama_jenis_hewan.min' => 'Nama jenis hewan minimal 3 karakter.',
        ]);
    }

    public function createJenisHewan(array $data)
    {
        try {
            return DB::table('jenis_hewan')->insert([
                'nama_jenis_hewan' => $this->formatJenisHewanName($data['nama_jenis_hewan']),
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal menambahkan jenis hewan.');
        }
    }

    public function formatJenisHewanName($name)
    {
        return trim(ucwords(strtolower($name)));
    }
}