<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                    ->where('status', 1);
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
            ->first();

        if (!$roleUser) {
            return redirect()->route('admin.dokter.index')
                ->with('error', 'Dokter tidak ditemukan.');
        }

        // Cek apakah dokter masih punya rekam medis
        $jumlahRekamMedis = DB::table('rekam_medis')
            ->where('dokter_pemeriksa', $id)
            ->count();

        if ($jumlahRekamMedis > 0) {
            return redirect()->route('admin.dokter.index')
                ->with('error', 'Dokter tidak dapat dihapus karena masih memiliki rekam medis.');
        }

        // Soft delete: ubah status jadi 0
        DB::table('role_user')
            ->where('idrole_user', $id)
            ->update(['status' => 0]);

        return redirect()->route('admin.dokter.index')
            ->with('success', 'Dokter berhasil dinonaktifkan.');
    }
}