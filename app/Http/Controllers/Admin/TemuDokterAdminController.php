<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TemuDokterAdminController extends Controller
{
    // INDEX - List semua temu dokter dengan filter
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', date('Y-m-d'));
        $selectedStatus = $request->get('status');
        $selectedDoctor = $request->get('doctor');
        $selectedMonth = $request->get('month', date('Y-m'));
        $selectedYear = $request->get('year', date('Y'));

        // Query dasar untuk antrian
        $antrianQuery = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'td.deleted_at',
                'p.nama as nama_pet',
                'p.idpet',
                'u_dokter.nama as nama_dokter',
                'u_pemilik.nama as nama_pemilik',
                'td.idrole_user'
            )
            ->leftJoin('pet as p', 'p.idpet', '=', 'td.idpet')
            ->leftJoin('pemilik as pm', 'pm.idpemilik', '=', 'p.idpemilik')
            ->leftJoin('user as u_pemilik', 'u_pemilik.iduser', '=', 'pm.iduser')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->whereNull('td.deleted_at');

        // Apply filters
        if ($request->has('date') && $request->date) {
            $antrianQuery->whereDate('td.waktu_daftar', $selectedDate);
        } elseif ($request->has('month') && $request->month) {
            $year = date('Y', strtotime($selectedMonth));
            $month = date('m', strtotime($selectedMonth));
            $antrianQuery->whereYear('td.waktu_daftar', $year)
                        ->whereMonth('td.waktu_daftar', $month);
        } elseif ($request->has('year') && $request->year) {
            $antrianQuery->whereYear('td.waktu_daftar', $selectedYear);
        }

        if ($request->has('status') && $request->status !== null) {
            $antrianQuery->where('td.status', $selectedStatus);
        }

        if ($request->has('doctor') && $request->doctor) {
            $antrianQuery->where('td.idrole_user', $selectedDoctor);
        }

        // Default order by
        $antrian = $antrianQuery->orderBy('td.waktu_daftar', 'desc')->get();

        // Get available pets for add form
        $activePetIds = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $selectedDate)
            ->where('status', 0)
            ->whereNull('deleted_at')
            ->pluck('idpet')
            ->toArray();

        $pets = DB::table('pet as p')
            ->select('p.idpet', 'p.nama', 'u.nama as nama_pemilik')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->whereNull('p.deleted_at')
            ->whereNotIn('p.idpet', $activePetIds)
            ->get();

        // Get available doctors
        $doctors = DB::table('role_user as ru')
            ->select('ru.idrole_user', 'u.nama')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2) // Dokter
            ->where('ru.status', 1)
            ->whereNull('ru.deleted_at')
            ->get();

        // Get statistics
        $statistik = $this->getStatistik($request);

        return view('rshp.admin.DataMaster.temu-dokter.index', compact(
            'antrian',
            'pets',
            'doctors',
            'selectedDate',
            'selectedStatus',
            'selectedDoctor',
            'selectedMonth',
            'selectedYear',
            'statistik'
        ));
    }

    // Statistik
    private function getStatistik($request)
    {
        $selectedDate = $request->get('date', date('Y-m-d'));
        $selectedMonth = $request->get('month', date('Y-m'));
        $selectedYear = $request->get('year', date('Y'));

        $statistik = [
            'total' => 0,
            'menunggu' => 0,
            'selesai' => 0,
            'batal' => 0,
            'per_hari' => [],
            'per_bulan' => []
        ];

        // Base query
        $query = DB::table('temu_dokter')
            ->whereNull('deleted_at');

        if ($request->has('date') && $request->date) {
            $query->whereDate('waktu_daftar', $selectedDate);
            $statistik['total'] = $query->count();
            $statistik['menunggu'] = $query->where('status', 0)->count();
            $statistik['selesai'] = $query->where('status', 1)->count();
            $statistik['batal'] = $query->where('status', 2)->count();

        } elseif ($request->has('month') && $request->month) {
            $year = date('Y', strtotime($selectedMonth));
            $month = date('m', strtotime($selectedMonth));

            $query->whereYear('waktu_daftar', $year)
                  ->whereMonth('waktu_daftar', $month);

            $statistik['total'] = $query->count();
            $statistik['menunggu'] = $query->where('status', 0)->count();
            $statistik['selesai'] = $query->where('status', 1)->count();
            $statistik['batal'] = $query->where('status', 2)->count();

            // Per hari dalam bulan
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);
                $count = DB::table('temu_dokter')
                    ->whereDate('waktu_daftar', $currentDate)
                    ->whereNull('deleted_at')
                    ->count();
                $statistik['per_hari'][$currentDate] = $count;
            }

        } elseif ($request->has('year') && $request->year) {
            $query->whereYear('waktu_daftar', $selectedYear);
            
            $statistik['total'] = $query->count();
            $statistik['menunggu'] = $query->where('status', 0)->count();
            $statistik['selesai'] = $query->where('status', 1)->count();
            $statistik['batal'] = $query->where('status', 2)->count();

            // Per bulan dalam tahun
            for ($month = 1; $month <= 12; $month++) {
                $monthFormatted = sprintf('%02d', $month);
                $count = DB::table('temu_dokter')
                    ->whereYear('waktu_daftar', $selectedYear)
                    ->whereMonth('waktu_daftar', $monthFormatted)
                    ->whereNull('deleted_at')
                    ->count();
                $statistik['per_bulan'][$month] = $count;
            }

        } else {
            // All time statistics
            $statistik['total'] = DB::table('temu_dokter')
                ->whereNull('deleted_at')
                ->count();
            $statistik['menunggu'] = DB::table('temu_dokter')
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->count();
            $statistik['selesai'] = DB::table('temu_dokter')
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->count();
            $statistik['batal'] = DB::table('temu_dokter')
                ->where('status', 2)
                ->whereNull('deleted_at')
                ->count();
        }

        return $statistik;
    }

    // CREATE - Form tambah baru
    public function create()
    {
        $selectedDate = date('Y-m-d');

        // Get available pets
        $activePetIds = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $selectedDate)
            ->where('status', 0)
            ->whereNull('deleted_at')
            ->pluck('idpet')
            ->toArray();

        $pets = DB::table('pet as p')
            ->select('p.idpet', 'p.nama', 'u.nama as nama_pemilik')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->whereNull('p.deleted_at')
            ->whereNotIn('p.idpet', $activePetIds)
            ->get();

        // Get available doctors
        $doctors = DB::table('role_user as ru')
            ->select('ru.idrole_user', 'u.nama')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2)
            ->where('ru.status', 1)
            ->whereNull('ru.deleted_at')
            ->get();

        return view('rshp.admin.DataMaster.temu-dokter.create', compact('pets', 'doctors'));
    }

    // STORE - Tambah temu dokter baru
    public function store(Request $request)
    {
        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'idrole_user' => 'required|exists:role_user,idrole_user',
            'tanggal_daftar' => 'required|date|after_or_equal:today'
        ]);

        try {
            $idpet = $request->idpet;
            $idrole_user = $request->idrole_user;
            $tanggal_daftar = $request->tanggal_daftar;

            // Cek apakah pet sudah ada antrian aktif
            $existing = DB::table('temu_dokter')
                ->where('idpet', $idpet)
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->whereDate('waktu_daftar', $tanggal_daftar)
                ->exists();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Pet sudah berada dalam antrian pada tanggal ' . date('d-m-Y', strtotime($tanggal_daftar)))
                    ->withInput();
            }

            // Get next queue number
            $nextNo = DB::table('temu_dokter')
                ->whereDate('waktu_daftar', $tanggal_daftar)
                ->where('idrole_user', $idrole_user)
                ->whereNull('deleted_at')
                ->max('no_urut') ?? 0;
            $nextNo++;

            // Create antrian
            DB::table('temu_dokter')->insert([
                'idpet' => $idpet,
                'idrole_user' => $idrole_user,
                'no_urut' => $nextNo,
                'waktu_daftar' => $tanggal_daftar . ' ' . date('H:i:s'),
                'status' => 0,
            ]);

            return redirect()->route('admin.temu-dokter.index')
                ->with('success', 'Antrian berhasil dibuat! Nomor antrian: ' . $nextNo);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat antrian: ' . $e->getMessage())
                ->withInput();
        }
    }

    // EDIT - Form edit temu dokter
    public function edit($id)
    {
        $temuDokter = DB::table('temu_dokter as td')
            ->select(
                'td.*',
                'p.nama as nama_pet',
                'p.idpet',
                'u_pemilik.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter'
            )
            ->leftJoin('pet as p', 'p.idpet', '=', 'td.idpet')
            ->leftJoin('pemilik as pm', 'pm.idpemilik', '=', 'p.idpemilik')
            ->leftJoin('user as u_pemilik', 'u_pemilik.iduser', '=', 'pm.iduser')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->where('td.idreservasi_dokter', $id)
            ->whereNull('td.deleted_at')
            ->first();

        if (!$temuDokter) {
            return redirect()->route('admin.temu-dokter.index')
                ->with('error', 'Temu dokter tidak ditemukan');
        }

        $selectedDate = date('Y-m-d', strtotime($temuDokter->waktu_daftar));

        // Get available pets (exclude yang sudah ada antrian kecuali yang sedang diedit)
        $activePetIds = DB::table('temu_dokter')
            ->whereDate('waktu_daftar', $selectedDate)
            ->where('status', 0)
            ->where('idreservasi_dokter', '!=', $id)
            ->whereNull('deleted_at')
            ->pluck('idpet')
            ->toArray();

        $pets = DB::table('pet as p')
            ->select('p.idpet', 'p.nama', 'u.nama as nama_pemilik')
            ->join('pemilik as pm', 'p.idpemilik', '=', 'pm.idpemilik')
            ->join('user as u', 'pm.iduser', '=', 'u.iduser')
            ->whereNull('p.deleted_at')
            ->where(function($query) use ($activePetIds, $temuDokter) {
                $query->whereNotIn('p.idpet', $activePetIds)
                      ->orWhere('p.idpet', $temuDokter->idpet);
            })
            ->get();

        // Get available doctors
        $doctors = DB::table('role_user as ru')
            ->select('ru.idrole_user', 'u.nama')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2)
            ->where('ru.status', 1)
            ->whereNull('ru.deleted_at')
            ->get();

        return view('rshp.admin.DataMaster.temu-dokter.edit', compact('temuDokter', 'pets', 'doctors'));
    }

    // UPDATE - Update temu dokter
    public function update(Request $request, $id)
    {
        $request->validate([
            'idpet' => 'required|exists:pet,idpet',
            'idrole_user' => 'required|exists:role_user,idrole_user',
            'tanggal_daftar' => 'required|date',
            'status' => 'required|in:0,1,2'
        ]);

        try {
            // Cek existing
            $existing = DB::table('temu_dokter')
                ->where('idpet', $request->idpet)
                ->where('status', 0)
                ->where('idreservasi_dokter', '!=', $id)
                ->whereNull('deleted_at')
                ->whereDate('waktu_daftar', $request->tanggal_daftar)
                ->exists();

            if ($existing) {
                return redirect()->back()
                    ->with('error', 'Pet sudah memiliki antrian aktif pada tanggal tersebut')
                    ->withInput();
            }

            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $id)
                ->update([
                    'idpet' => $request->idpet,
                    'idrole_user' => $request->idrole_user,
                    'waktu_daftar' => $request->tanggal_daftar . ' ' . date('H:i:s'),
                    'status' => $request->status,
                ]);

            return redirect()->route('admin.temu-dokter.index')
                ->with('success', 'Temu dokter berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate temu dokter: ' . $e->getMessage())
                ->withInput();
        }
    }

    // UPDATE STATUS
    public function updateStatus(Request $request)
    {
        $request->validate([
            'idreservasi_dokter' => 'required|exists:temu_dokter,idreservasi_dokter',
            'status' => 'required|in:1,2'
        ]);

        try {
            $updated = DB::table('temu_dokter')
                ->where('idreservasi_dokter', $request->idreservasi_dokter)
                ->where('status', 0)
                ->whereNull('deleted_at')
                ->update(['status' => $request->status]);

            if ($updated) {
                $statusText = $request->status == 1 ? 'selesai' : 'dibatalkan';
                return redirect()->back()
                    ->with('success', 'Status antrian berhasil diubah menjadi ' . $statusText);
            }

            return redirect()->back()
                ->with('error', 'Gagal mengubah status antrian');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }

    // SOFT DELETE
    public function destroy($id)
    {
        try {
            $updated = DB::table('temu_dokter')
                ->where('idreservasi_dokter', $id)
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => Auth::id()
                ]);

            if ($updated) {
                return redirect()->route('admin.temu-dokter.index')
                    ->with('success', 'Temu dokter berhasil dihapus');
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus temu dokter');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // TRASH - List soft deleted items
    public function trash()
    {
        $trashedItems = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'td.status',
                'td.deleted_at',
                'p.nama as nama_pet',
                'u_dokter.nama as nama_dokter',
                'u_pemilik.nama as nama_pemilik',
                'u_deleted.nama as deleted_by_nama'
            )
            ->leftJoin('pet as p', 'p.idpet', '=', 'td.idpet')
            ->leftJoin('pemilik as pm', 'pm.idpemilik', '=', 'p.idpemilik')
            ->leftJoin('user as u_pemilik', 'u_pemilik.iduser', '=', 'pm.iduser')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'td.idrole_user')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->leftJoin('user as u_deleted', 'u_deleted.iduser', '=', 'td.deleted_by')
            ->whereNotNull('td.deleted_at')
            ->orderBy('td.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.temu-dokter.trash', compact('trashedItems'));
    }

    // RESTORE
    public function restore($id)
    {
        try {
            $updated = DB::table('temu_dokter')
                ->where('idreservasi_dokter', $id)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);

            if ($updated) {
                return redirect()->route('admin.temu-dokter.trash')
                    ->with('success', 'Temu dokter berhasil dipulihkan');
            }

            return redirect()->back()
                ->with('error', 'Gagal memulihkan temu dokter');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memulihkan: ' . $e->getMessage());
        }
    }

    // FORCE DELETE
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            // Hapus rekam medis terkait dulu
            DB::table('rekam_medis')
                ->where('idreservasi_dokter', $id)
                ->delete();

            // Hapus temu dokter
            $deleted = DB::table('temu_dokter')
                ->where('idreservasi_dokter', $id)
                ->delete();

            DB::commit();

            if ($deleted) {
                return redirect()->route('admin.temu-dokter.trash')
                    ->with('success', 'Temu dokter berhasil dihapus permanen');
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus permanen');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus permanen: ' . $e->getMessage());
        }
    }
}
