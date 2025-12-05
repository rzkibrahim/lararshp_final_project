<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('user')
            ->leftJoin('role_user', function ($join) {
                $join->on('user.iduser', '=', 'role_user.iduser')
                    ->whereNull('role_user.deleted_at');
            })
            ->leftJoin('role', 'role_user.idrole', '=', 'role.idrole')
            ->select(
                'user.iduser',
                'user.nama',
                'user.email',
                DB::raw('GROUP_CONCAT(role.nama_role SEPARATOR ", ") as roles')
            )
            ->whereNull('user.deleted_at')
            ->groupBy('user.iduser', 'user.nama', 'user.email')
            ->orderBy('user.iduser', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.user.index', compact('users'));
    }

    public function create()
    {
        $roles = DB::table('role')
            ->orderBy('nama_role', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:3|confirmed',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:role,idrole',
        ]);

        // Simpan user ke tabel user
        $userId = DB::table('user')->insertGetId([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ], 'iduser');

        // Simpan role jika ada
        if (!empty($validated['role_ids'])) {
            $roleData = collect($validated['role_ids'])->map(function ($idrole) use ($userId) {
                return [
                    'idrole' => $idrole,
                    'iduser' => $userId,
                ];
            })->toArray();

            DB::table('role_user')->insert($roleData);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = DB::table('user')->where('iduser', $id)->first();

        if (!$user) {
            abort(404);
        }

        $roles = DB::table('role')
            ->orderBy('nama_role', 'asc')
            ->get();

        // Ambil role yang dimiliki user
        $userRoles = DB::table('role_user')
            ->where('iduser', $id)
            ->pluck('idrole')
            ->toArray();

        return view('rshp.admin.DataMaster.user.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id . ',iduser',
            'password' => 'nullable|min:3|confirmed',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:role,idrole',
        ]);

        // Cek apakah user ada
        $user = DB::table('user')->where('iduser', $id)->first();
        if (!$user) {
            abort(404);
        }

        // Siapkan data update
        $updateData = [
            'nama' => $validated['nama_lengkap'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Update data user
        DB::table('user')->where('iduser', $id)->update($updateData);

        // Update role (hapus lama → insert baru)
        DB::table('role_user')->where('iduser', $id)->delete();

        if (!empty($validated['role_ids'])) {
            $newRoles = collect($validated['role_ids'])->map(function ($idrole) use ($id) {
                return [
                    'idrole' => $idrole,
                    'iduser' => $id,
                ];
            })->toArray();

            DB::table('role_user')->insert($newRoles);
        }

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Soft delete user
        DB::table('user')
            ->where('iduser', $id)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id()
            ]);

        // Soft delete semua role_user terkait
        DB::table('role_user')
            ->where('iduser', $id)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id()
            ]);

        return redirect()->route('admin.user.index')
            ->with('success', 'User berhasil dipindahkan ke trash.');
    }

    public function trash()
    {
        $users = DB::table('user')
            ->leftJoin('user as deleter', 'user.deleted_by', '=', 'deleter.iduser')
            ->select(
                'user.*',
                'deleter.nama as deleted_by_name'
            )
            ->whereNotNull('user.deleted_at')
            ->orderBy('user.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.user.trash', compact('users'));
    }

    public function restore($id)
    {
        // Restore user
        DB::table('user')
            ->where('iduser', $id)
            ->update([
                'deleted_at' => null,
                'deleted_by' => null
            ]);

        // Restore role_user terkait
        DB::table('role_user')
            ->where('iduser', $id)
            ->update([
                'deleted_at' => null,
                'deleted_by' => null
            ]);

        return redirect()->route('admin.user.trash')
            ->with('success', 'User berhasil dikembalikan!');
    }

    public function forceDelete($id)
    {
        // Hard delete role_user dulu
        DB::table('role_user')->where('iduser', $id)->delete();

        // Hard delete user
        DB::table('user')->where('iduser', $id)->delete();

        return redirect()->route('admin.user.trash')
            ->with('success', 'User berhasil dihapus permanen!');
    }
}
