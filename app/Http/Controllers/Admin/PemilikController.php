<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    public function index()
    {
        $pemiliks = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select(
                'pemilik.idpemilik',
                'pemilik.iduser',
                'user.nama',
                'user.email',
                'pemilik.alamat',
                'pemilik.no_wa'
            )
            ->whereNull('pemilik.deleted_at') // ✅ Tambahkan filter untuk data aktif
            ->orderBy('pemilik.idpemilik', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        // Ambil user yang belum punya pemilik
        $users = DB::table('user')
            ->whereNotIn('iduser', function ($query) {
                $query->select('iduser')->from('pemilik')
                    ->whereNull('deleted_at'); // Hanya user dengan pemilik aktif
            })
            ->get();

        return view('rshp.admin.DataMaster.pemilik.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePemilik($request);

        DB::table('pemilik')->insert([
            'iduser' => $validated['iduser'],
            'alamat' => $validated['alamat'],
            'no_wa'  => $this->formatNoWa($validated['no_wa']),
        ]);

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemilik = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->select(
                'pemilik.idpemilik',
                'pemilik.iduser',
                'user.nama',
                'user.email',
                'pemilik.alamat',
                'pemilik.no_wa'
            )
            ->where('pemilik.idpemilik', $id)
            ->whereNull('pemilik.deleted_at')
            ->first();

        if (!$pemilik) {
            abort(404, 'Data pemilik tidak ditemukan.');
        }

        return view('rshp.admin.DataMaster.pemilik.edit', compact('pemilik'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required|string|max:500',
            'email'  => 'required|email|max:200',
            'alamat' => 'required|string|max:1000',
            'no_wa'  => 'required|string|max:45',
        ]);

        $pemilik = DB::table('pemilik')->where('idpemilik', $id)->first();

        if (!$pemilik) {
            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Data pemilik tidak ditemukan.');
        }

        // Update user
        DB::table('user')
            ->where('iduser', $pemilik->iduser)
            ->update([
                'nama'  => $request->nama,
                'email' => $request->email,
            ]);

        // Update pemilik
        DB::table('pemilik')
            ->where('idpemilik', $id)
            ->update([
                'alamat' => $request->alamat,
                'no_wa'  => $request->no_wa,
            ]);

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Data pemilik berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $jumlahPet = DB::table('pet')
            ->where('idpemilik', $id)
            ->whereNull('deleted_at')
            ->count();

        if ($jumlahPet > 0) {
            return redirect()->route('admin.pemilik.index')
                ->with('error', 'Pemilik tidak dapat dihapus karena masih memiliki pet.');
        }

        DB::table('pemilik')->where('idpemilik', $id)->update([
            'deleted_at' => now(),
            'deleted_by' => Auth::id()
        ]);

        return redirect()->route('admin.pemilik.index')
            ->with('success', 'Pemilik berhasil dipindahkan ke trash.');
    }

    // ✅ TAMBAHKAN TRASH, RESTORE, FORCE DELETE
    public function trash()
    {
        $pemiliks = DB::table('pemilik')
            ->join('user', 'pemilik.iduser', '=', 'user.iduser')
            ->leftJoin('user as deleter', 'pemilik.deleted_by', '=', 'deleter.iduser')
            ->select(
                'pemilik.*',
                'user.nama',
                'user.email',
                'deleter.nama as deleted_by_name'
            )
            ->whereNotNull('pemilik.deleted_at')
            ->orderBy('pemilik.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.pemilik.trash', compact('pemiliks'));
    }

    public function restore($id)
    {
        DB::table('pemilik')->where('idpemilik', $id)->update([
            'deleted_at' => null,
            'deleted_by' => null
        ]);

        return redirect()->route('admin.pemilik.trash')
            ->with('success', 'Pemilik berhasil dikembalikan!');
    }

    public function forceDelete($id)
    {
        // Cek apakah masih memiliki pet (termasuk yang di-trash)
        $jumlahPet = DB::table('pet')
            ->where('idpemilik', $id)
            ->exists();

        if ($jumlahPet > 0) {
            return redirect()->route('admin.pemilik.trash')
                ->with('error', 'Tidak dapat menghapus permanen karena masih memiliki data pet!');
        }

        DB::table('pemilik')->where('idpemilik', $id)->delete();

        return redirect()->route('admin.pemilik.trash')
            ->with('success', 'Pemilik berhasil dihapus permanen!');
    }

    // ==================== VALIDASI & HELPER ====================

    protected function validatePemilik(Request $request, $id = null)
    {
        $userUniqueRule = $id
            ? 'unique:pemilik,iduser,' . $id . ',idpemilik,deleted_at,NULL'
            : 'unique:pemilik,iduser,NULL,idpemilik,deleted_at,NULL';

        return $request->validate([
            'iduser' => [
                'required',
                'exists:user,iduser',
                $userUniqueRule
            ],
            'alamat' => [
                'required',
                'string',
                'max:1000',
                'min:10'
            ],
            'no_wa' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9+\-\s()]*$/'
            ],
        ], [
            'iduser.required' => 'User wajib dipilih.',
            'iduser.exists' => 'User tidak valid.',
            'iduser.unique' => 'User ini sudah terdaftar sebagai pemilik.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'no_wa.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa.max' => 'Nomor WhatsApp maksimal 15 karakter.',
            'no_wa.regex' => 'Format nomor WhatsApp tidak valid.',
        ]);
    }

    protected function formatNoWa($no)
    {
        $no = preg_replace('/[^0-9]/', '', $no);
        if (substr($no, 0, 1) === '0') {
            $no = '+62' . substr($no, 1);
        }
        return $no;
    }
}
