<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        $roles = DB::table('role')
            ->leftJoin('role_user', function ($join) {
                $join->on('role.idrole', '=', 'role_user.idrole')
                    ->whereNull('role_user.deleted_at');
            })
            ->select(
                'role.idrole',
                'role.nama_role',
                DB::raw('COUNT(role_user.iduser) as jumlah_user')
            )
            ->whereNull('role.deleted_at')
            ->groupBy('role.idrole', 'role.nama_role')
            ->orderBy('role.idrole', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.role.index', compact('roles'));
    }

    public function create()
    {
        return view('rshp.admin.DataMaster.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role'
        ], [
            'nama_role.required' => 'Nama role wajib diisi',
            'nama_role.unique' => 'Nama role sudah ada',
            'nama_role.max' => 'Nama role maksimal 100 karakter'
        ]);

        DB::table('role')->insert([
            'nama_role' => trim(ucwords(strtolower($request->nama_role)))
        ]);

        return redirect()->route('admin.role.index')
            ->with('success', 'Role berhasil ditambahkan');
    }

    public function edit($id)
    {
        $role = DB::table('role')->where('idrole', $id)->first();

        if (!$role) {
            abort(404, 'Role tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role,' . $id . ',idrole'
        ], [
            'nama_role.required' => 'Nama role wajib diisi',
            'nama_role.unique' => 'Nama role sudah ada',
            'nama_role.max' => 'Nama role maksimal 100 karakter'
        ]);

        DB::table('role')->where('idrole', $id)->update([
            'nama_role' => trim(ucwords(strtolower($request->nama_role)))
        ]);

        return redirect()->route('admin.role.index')
            ->with('success', 'Role berhasil diupdate');
    }

    public function destroy($id)
    {
        $usedByUser = DB::table('role_user')
            ->where('idrole', $id)
            ->whereNull('deleted_at')
            ->count();

        if ($usedByUser > 0) {
            return redirect()->route('admin.role.index')
                ->with('error', 'Role tidak dapat dihapus karena sedang digunakan oleh user.');
        }

        DB::table('role')->where('idrole', $id)->update([
            'deleted_at' => now(),
            'deleted_by' => Auth::id()
        ]);

        return redirect()->route('admin.role.index')
            ->with('success', 'Role berhasil dipindahkan ke trash');
    }

    // ✅ TAMBAHKAN TRASH, RESTORE, FORCE DELETE
    public function trash()
    {
        $roles = DB::table('role')
            ->leftJoin('user', 'role.deleted_by', '=', 'user.iduser')
            ->select(
                'role.*',
                'user.nama as deleted_by_name'
            )
            ->whereNotNull('role.deleted_at')
            ->orderBy('role.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.role.trash', compact('roles'));
    }

    public function restore($id)
    {
        DB::table('role')->where('idrole', $id)->update([
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        return redirect()->route('admin.role.trash')
            ->with('success', 'Role berhasil dikembalikan!');
    }

    public function forceDelete($id)
    {
        // Cek apakah masih digunakan di role_user (termasuk yang di-trash)
        $usedByUser = DB::table('role_user')
            ->where('idrole', $id)
            ->exists();

        if ($usedByUser) {
            return redirect()->route('admin.role.trash')
                ->with('error', 'Tidak dapat menghapus permanen karena masih digunakan oleh user!');
        }

        DB::table('role')->where('idrole', $id)->delete();

        return redirect()->route('admin.role.trash')
            ->with('success', 'Role berhasil dihapus permanen!');
    }
}
