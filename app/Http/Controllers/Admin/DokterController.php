<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokterController extends Controller
{
    public function index()
    {
        // Ambil semua user yang memiliki role dokter (idrole = 2)
        $dokters = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'ru.idrole', '=', 'r.idrole')
            ->select(
                'ru.idrole_user',
                'ru.iduser',
                'ru.idrole',
                'ru.status',
                'u.nama',
                'u.email',
                'r.nama_role'
            )
            ->where('ru.idrole', 2) // 2 = Dokter
            ->whereNull('ru.deleted_at') // Tambahkan filter untuk data aktif
            ->orderBy('ru.idrole_user', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.dokter.index', compact('dokters'));
    }

    public function create()
    {
        // Ambil user yang belum memiliki role dokter
        $users = DB::table('user')
            ->whereNotIn('iduser', function ($query) {
                $query->select('iduser')
                    ->from('role_user')
                    ->where('idrole', 2)
                    ->where('status', 1)
                    ->whereNull('deleted_at');
            })
            ->orderBy('nama', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.dokter.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'iduser' => [
                'required',
                'exists:user,iduser',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('role_user')
                        ->where('iduser', $value)
                        ->where('idrole', 2)
                        ->where('status', 1)
                        ->whereNull('deleted_at')
                        ->exists();

                    if ($exists) {
                        $fail('User ini sudah terdaftar sebagai dokter.');
                    }
                }
            ],
            'status' => 'required|in:0,1',
        ], [
            'iduser.required' => 'User wajib dipilih.',
            'iduser.exists' => 'User tidak valid.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        DB::table('role_user')->insert([
            'iduser' => $validated['iduser'],
            'idrole' => 2, // 2 = Dokter
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $dokter = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->select(
                'ru.idrole_user',
                'ru.iduser',
                'ru.idrole',
                'ru.status',
                'u.nama',
                'u.email'
            )
            ->where('ru.idrole_user', $id)
            ->where('ru.idrole', 2)
            ->whereNull('ru.deleted_at')
            ->first();

        if (!$dokter) {
            abort(404, 'Data dokter tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:500',
            'email' => 'required|email|max:200',
            'status' => 'required|in:0,1',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 500 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 200 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        $roleUser = DB::table('role_user')
            ->where('idrole_user', $id)
            ->where('idrole', 2)
            ->whereNull('deleted_at')
            ->first();

        if (!$roleUser) {
            return redirect()->route('admin.dokter.index')
                ->with('error', 'Data dokter tidak ditemukan.');
        }

        // Update data user
        DB::table('user')
            ->where('iduser', $roleUser->iduser)
            ->update([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
            ]);

        // Update status role_user
        DB::table('role_user')
            ->where('idrole_user', $id)
            ->update([
                'status' => $validated['status'],
            ]);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $roleUser = DB::table('role_user')
            ->where('idrole_user', $id)
            ->where('idrole', 2)
            ->whereNull('deleted_at')
            ->first();

        if (!$roleUser) {
            return redirect()->route('admin.dokter.index')
                ->with('error', 'Dokter tidak ditemukan.');
        }

        // Cek apakah dokter masih punya rekam medis
        $jumlahRekamMedis = DB::table('rekam_medis')
            ->where('dokter_pemeriksa', $id)
            ->whereNull('deleted_at')
            ->count();

        if ($jumlahRekamMedis > 0) {
            return redirect()->route('admin.dokter.index')
                ->with('error', 'Dokter tidak dapat dihapus karena masih memiliki rekam medis.');
        }

        // Soft delete role_user
        DB::table('role_user')->where('idrole_user', $id)->update([
            'deleted_at' => now(),
            'deleted_by' => Auth::id()
        ]);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil dipindahkan ke trash.');
    }

    // ✅ TAMBAHKAN TRASH, RESTORE, FORCE DELETE
    public function trash()
    {
        $dokters = DB::table('role_user as ru')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->join('role as r', 'ru.idrole', '=', 'r.idrole')
            ->leftJoin('user as deleter', 'ru.deleted_by', '=', 'deleter.iduser')
            ->select(
                'ru.*',
                'u.nama',
                'u.email',
                'r.nama_role',
                'deleter.nama as deleted_by_name'
            )
            ->where('ru.idrole', 2)
            ->whereNotNull('ru.deleted_at')
            ->orderBy('ru.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.dokter.trash', compact('dokters'));
    }

    public function restore($id)
    {
        $roleUser = DB::table('role_user')
            ->where('idrole_user', $id)
            ->where('idrole', 2)
            ->whereNotNull('deleted_at')
            ->first();

        if (!$roleUser) {
            return redirect()->route('admin.dokter.trash')
                ->with('error', 'Data dokter tidak ditemukan di trash.');
        }

        DB::table('role_user')->where('idrole_user', $id)->update([
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        return redirect()->route('admin.dokter.trash')
            ->with('success', 'Dokter berhasil dikembalikan!');
    }

    public function forceDelete($id)
    {
        // Cek apakah masih punya rekam medis
        $jumlahRekamMedis = DB::table('rekam_medis')
            ->where('dokter_pemeriksa', $id)
            ->exists();

        if ($jumlahRekamMedis > 0) {
            return redirect()->route('admin.dokter.trash')
                ->with('error', 'Tidak dapat menghapus permanen karena masih memiliki rekam medis!');
        }

        DB::table('role_user')->where('idrole_user', $id)->delete();

        return redirect()->route('admin.dokter.trash')
            ->with('success', 'Dokter berhasil dihapus permanen!');
    }
}
