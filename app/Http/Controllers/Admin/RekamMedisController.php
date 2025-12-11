<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RekamMedisController extends Controller
{
    // INDEX - List reservasi dan rekam medis dengan filter tanggal
    public function index(Request $request)
    {
        // Handle navigation actions
        if ($request->has('action')) {
            switch ($request->action) {
                case 'prev_month':
                    $bulan = Carbon::parse($request->bulan)->subMonth()->format('Y-m');
                    return redirect()->route('admin.rekam-medis.index', ['bulan' => $bulan]);
                    
                case 'next_month':
                    $bulan = Carbon::parse($request->bulan)->addMonth()->format('Y-m');
                    return redirect()->route('admin.rekam-medis.index', ['bulan' => $bulan]);
                    
                case 'filter':
                    break;
            }
        }

        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $bulan = $request->get('bulan', date('Y-m'));
        $tahun = $request->get('tahun', date('Y'));

        // Ambil reservasi tanpa rekam medis
        $reservasiQuery = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.idpet',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->leftJoin('rekam_medis as rm', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->whereNull('rm.idrekam_medis')
            ->whereNull('td.deleted_at');

        if ($request->has('tanggal') && $request->tanggal) {
            $reservasiQuery->whereDate('td.waktu_daftar', $tanggal);
        } elseif ($request->has('bulan') && $request->bulan) {
            $reservasiQuery->whereYear('td.waktu_daftar', date('Y', strtotime($bulan)))
                ->whereMonth('td.waktu_daftar', date('m', strtotime($bulan)));
        } elseif ($request->has('tahun') && $request->tahun) {
            $reservasiQuery->whereYear('td.waktu_daftar', $tahun);
        }

        $reservasi = $reservasiQuery->orderBy('td.waktu_daftar', 'desc')->get();

        // Ambil rekam medis yang sudah ada dengan COUNT detail tindakan
        $listRMQuery = DB::table('rekam_medis as rm')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'rm.idreservasi_dokter',
                'rm.deleted_at',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik',
                'td.waktu_daftar',
                DB::raw('(SELECT COUNT(*) FROM detail_rekam_medis drm WHERE drm.idrekam_medis = rm.idrekam_medis AND drm.deleted_at IS NULL) as jumlah_tindakan')
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->join('temu_dokter as td', 'rm.idreservasi_dokter', '=', 'td.idreservasi_dokter')
            ->whereNull('rm.deleted_at');

        if ($request->has('tanggal') && $request->tanggal) {
            $listRMQuery->whereDate('rm.created_at', $tanggal);
        } elseif ($request->has('bulan') && $request->bulan) {
            $listRMQuery->whereYear('rm.created_at', date('Y', strtotime($bulan)))
                ->whereMonth('rm.created_at', date('m', strtotime($bulan)));
        } elseif ($request->has('tahun') && $request->tahun) {
            $listRMQuery->whereYear('rm.created_at', $tahun);
        }

        $listRM = $listRMQuery->orderBy('rm.created_at', 'desc')->get();

        $statistik = $this->getStatistikKalender($request);

        return view('rshp.admin.DataMaster.rekam-medis.index', compact(
            'reservasi',
            'listRM',
            'tanggal',
            'bulan',
            'tahun',
            'statistik'
        ));
    }

    // Statistik Kalender
    private function getStatistikKalender($request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $bulan = $request->get('bulan', date('Y-m'));
        $tahun = $request->get('tahun', date('Y'));

        $statistik = [
            'total_reservasi' => 0,
            'total_rekam_medis' => 0,
            'reservasi_per_hari' => [],
            'rekam_medis_per_hari' => [],
            'reservasi_per_bulan' => [],
            'rekam_medis_per_bulan' => [],
            'total_tindakan' => 0
        ];

        if ($request->has('tanggal') && $request->tanggal) {
            $statistik['total_reservasi'] = DB::table('temu_dokter')
                ->whereDate('waktu_daftar', $tanggal)
                ->whereNull('deleted_at')
                ->count();

            $statistik['total_rekam_medis'] = DB::table('rekam_medis')
                ->whereDate('created_at', $tanggal)
                ->whereNull('deleted_at')
                ->count();

            // Hitung total tindakan untuk tanggal tertentu
            $statistik['total_tindakan'] = DB::table('detail_rekam_medis as drm')
                ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
                ->whereDate('rm.created_at', $tanggal)
                ->whereNull('drm.deleted_at')
                ->whereNull('rm.deleted_at')
                ->count();

        } elseif ($request->has('bulan') && $request->bulan) {
            $year = date('Y', strtotime($bulan));
            $month = date('m', strtotime($bulan));

            $statistik['total_reservasi'] = DB::table('temu_dokter')
                ->whereYear('waktu_daftar', $year)
                ->whereMonth('waktu_daftar', $month)
                ->whereNull('deleted_at')
                ->count();

            $statistik['total_rekam_medis'] = DB::table('rekam_medis')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereNull('deleted_at')
                ->count();

            // Hitung total tindakan untuk bulan tertentu
            $statistik['total_tindakan'] = DB::table('detail_rekam_medis as drm')
                ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
                ->whereYear('rm.created_at', $year)
                ->whereMonth('rm.created_at', $month)
                ->whereNull('drm.deleted_at')
                ->whereNull('rm.deleted_at')
                ->count();

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = sprintf('%04d-%02d-%02d', $year, $month, $day);

                $reservasiCount = DB::table('temu_dokter')
                    ->whereDate('waktu_daftar', $currentDate)
                    ->whereNull('deleted_at')
                    ->count();

                $rekamMedisCount = DB::table('rekam_medis')
                    ->whereDate('created_at', $currentDate)
                    ->whereNull('deleted_at')
                    ->count();

                $statistik['reservasi_per_hari'][$currentDate] = $reservasiCount;
                $statistik['rekam_medis_per_hari'][$currentDate] = $rekamMedisCount;
            }
        } elseif ($request->has('tahun') && $request->tahun) {
            $statistik['total_reservasi'] = DB::table('temu_dokter')
                ->whereYear('waktu_daftar', $tahun)
                ->whereNull('deleted_at')
                ->count();

            $statistik['total_rekam_medis'] = DB::table('rekam_medis')
                ->whereYear('created_at', $tahun)
                ->whereNull('deleted_at')
                ->count();

            // Hitung total tindakan untuk tahun tertentu
            $statistik['total_tindakan'] = DB::table('detail_rekam_medis as drm')
                ->join('rekam_medis as rm', 'drm.idrekam_medis', '=', 'rm.idrekam_medis')
                ->whereYear('rm.created_at', $tahun)
                ->whereNull('drm.deleted_at')
                ->whereNull('rm.deleted_at')
                ->count();

            for ($month = 1; $month <= 12; $month++) {
                $monthFormatted = sprintf('%02d', $month);
                
                $reservasiCount = DB::table('temu_dokter')
                    ->whereYear('waktu_daftar', $tahun)
                    ->whereMonth('waktu_daftar', $monthFormatted)
                    ->whereNull('deleted_at')
                    ->count();

                $rekamMedisCount = DB::table('rekam_medis')
                    ->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $monthFormatted)
                    ->whereNull('deleted_at')
                    ->count();

                $statistik['reservasi_per_bulan'][$month] = $reservasiCount;
                $statistik['rekam_medis_per_bulan'][$month] = $rekamMedisCount;
            }
        }

        return $statistik;
    }

    // CREATE - Form buat rekam medis baru
    public function create($idReservasi, $idPet)
    {
        $exist = DB::table('rekam_medis')
            ->where('idreservasi_dokter', $idReservasi)
            ->whereNull('deleted_at')
            ->first();

        if ($exist) {
            return redirect()->route('admin.rekam-medis.detail', $exist->idrekam_medis)
                ->with('success', 'Rekam medis sudah ada');
        }

        $info = DB::table('temu_dokter as td')
            ->select(
                'td.idreservasi_dokter',
                'td.no_urut',
                'td.waktu_daftar',
                'p.idpet',
                'p.nama as nama_pet',
                'u.nama as nama_pemilik'
            )
            ->join('pet as p', 'td.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u', 'pem.iduser', '=', 'u.iduser')
            ->where('td.idreservasi_dokter', $idReservasi)
            ->whereNull('td.deleted_at')
            ->first();

        if (!$info) {
            return redirect()->route('admin.rekam-medis.index')
                ->with('error', 'Reservasi tidak ditemukan');
        }

        $listDokter = DB::table('role_user as ru')
            ->select('ru.idrole_user', 'u.nama')
            ->join('user as u', 'ru.iduser', '=', 'u.iduser')
            ->where('ru.idrole', 2)
            ->where('ru.status', 1)
            ->whereNull('ru.deleted_at')
            ->orderBy('u.nama')
            ->get();

        return view('rshp.admin.DataMaster.rekam-medis.create', compact('info', 'listDokter'));
    }

    // STORE - Simpan rekam medis baru
    public function store(Request $request)
    {
        $request->validate([
            'idreservasi' => 'required|exists:temu_dokter,idreservasi_dokter',
            'idpet' => 'required|exists:pet,idpet',
            'dokter_pemeriksa' => 'required|exists:role_user,idrole_user',
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $idRekam = DB::table('rekam_medis')->insertGetId([
                'idreservasi_dokter' => $request->idreservasi,
                'idpet' => $request->idpet,
                'dokter_pemeriksa' => $request->dokter_pemeriksa,
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
                'created_at' => now(),
            ]);

            DB::table('temu_dokter')
                ->where('idreservasi_dokter', $request->idreservasi)
                ->update(['status' => 1]);

            DB::commit();

            return redirect()->route('admin.rekam-medis.detail', $idRekam)
                ->with('success', 'Rekam medis berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membuat rekam medis: ' . $e->getMessage());
        }
    }

    // DETAIL - Edit header dan manage tindakan
    public function detail($id)
    {
        $header = DB::table('rekam_medis as rm')
            ->select(
                'rm.*',
                'p.nama as nama_pet',
                'u_pemilik.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'td.idreservasi_dokter'
            )
            ->join('temu_dokter as td', 'td.idreservasi_dokter', '=', 'rm.idreservasi_dokter')
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u_pemilik', 'pem.iduser', '=', 'u_pemilik.iduser')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'rm.dokter_pemeriksa')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->where('rm.idrekam_medis', $id)
            ->whereNull('rm.deleted_at')
            ->first();

        if (!$header) {
            return redirect()->route('admin.rekam-medis.index')
                ->with('error', 'Rekam medis tidak ditemukan');
        }

        $detailTindakan = DB::table('detail_rekam_medis as drm')
            ->select(
                'drm.iddetail_rekam_medis',
                'drm.idrekam_medis',
                'drm.idkode_tindakan_terapi',
                'drm.detail',
                'ktt.kode',
                'ktt.deskripsi_tindakan_terapi',
                'k.nama_kategori',
                'kk.nama_kategori_klinis'
            )
            ->join('kode_tindakan_terapi as ktt', 'drm.idkode_tindakan_terapi', '=', 'ktt.idkode_tindakan_terapi')
            ->leftJoin('kategori as k', 'ktt.idkategori', '=', 'k.idkategori')
            ->leftJoin('kategori_klinis as kk', 'ktt.idkategori_klinis', '=', 'kk.idkategori_klinis')
            ->where('drm.idrekam_medis', $id)
            ->whereNull('drm.deleted_at')
            ->orderBy('drm.iddetail_rekam_medis', 'desc')
            ->get();

        $listKode = DB::table('kode_tindakan_terapi')
            ->select('idkode_tindakan_terapi', DB::raw("CONCAT(kode, ' - ', deskripsi_tindakan_terapi) as label"))
            ->whereNull('deleted_at')
            ->orderBy('kode', 'asc')
            ->get();

        return view('rshp.admin.DataMaster.rekam-medis.detail', compact('header', 'detailTindakan', 'listKode'));
    }

    // UPDATE_HEADER
    public function updateHeader(Request $request, $id)
    {
        $request->validate([
            'anamnesa' => 'nullable|string',
            'temuan_klinis' => 'nullable|string',
            'diagnosa' => 'nullable|string',
        ]);

        $updated = DB::table('rekam_medis')
            ->where('idrekam_medis', $id)
            ->whereNull('deleted_at')
            ->update([
                'anamnesa' => $request->anamnesa,
                'temuan_klinis' => $request->temuan_klinis,
                'diagnosa' => $request->diagnosa,
            ]);

        if ($updated) {
            return redirect()->route('admin.rekam-medis.detail', $id)
                ->with('success', 'Data pemeriksaan berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data pemeriksaan');
    }

    // CREATE_DETAIL
    public function createDetail(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        $created = DB::table('detail_rekam_medis')->insert([
            'idrekam_medis' => $id,
            'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
            'detail' => $request->detail,
        ]);

        if ($created) {
            // PERBAIKAN DI SINI: Menggunakan route name yang benar
            return redirect()->route('admin.rekam-medis.detail', $id)
                ->with('success', 'Tindakan berhasil ditambahkan');
        }

        return redirect()->back()->with('error', 'Gagal menambahkan tindakan');
    }

    // UPDATE_DETAIL
    public function updateDetail(Request $request, $id, $idDetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string',
        ]);

        $updated = DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $idDetail)
            ->whereNull('deleted_at')
            ->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

        if ($updated) {
            // PERBAIKAN DI SINI: Menggunakan route name yang benar
            return redirect()->route('admin.rekam-medis.detail', $id)
                ->with('success', 'Tindakan berhasil diperbarui');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui tindakan');
    }

    // DELETE_DETAIL
    public function deleteDetail(Request $request, $id, $idDetail)
    {
        $updated = DB::table('detail_rekam_medis')
            ->where('iddetail_rekam_medis', $idDetail)
            ->update([
                'deleted_at' => now(),
                'deleted_by' => Auth::id()
            ]);

        if ($updated) {
            // PERBAIKAN DI SINI: Menggunakan route name yang benar
            return redirect()->route('admin.rekam-medis.detail', $id)
                ->with('success', 'Tindakan berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Gagal menghapus tindakan');
    }

    // SOFT DELETE REKAM MEDIS
    public function destroy($id)
    {
        try {
            $updated = DB::table('rekam_medis')
                ->where('idrekam_medis', $id)
                ->update([
                    'deleted_at' => now(),
                    'deleted_by' => Auth::id()
                ]);

            if ($updated) {
                return redirect()->route('admin.rekam-medis.index')
                    ->with('success', 'Rekam medis berhasil dihapus');
            }

            return redirect()->back()->with('error', 'Gagal menghapus rekam medis');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    // TRASH
    public function trash()
    {
        $trashedItems = DB::table('rekam_medis as rm')
            ->select(
                'rm.idrekam_medis',
                'rm.created_at',
                'rm.anamnesa',
                'rm.diagnosa',
                'rm.deleted_at',
                'p.nama as nama_pet',
                'u_pemilik.nama as nama_pemilik',
                'u_dokter.nama as nama_dokter',
                'u_deleted.nama as deleted_by_nama',
                DB::raw('(SELECT COUNT(*) FROM detail_rekam_medis drm WHERE drm.idrekam_medis = rm.idrekam_medis) as jumlah_tindakan')
            )
            ->join('pet as p', 'rm.idpet', '=', 'p.idpet')
            ->join('pemilik as pem', 'p.idpemilik', '=', 'pem.idpemilik')
            ->join('user as u_pemilik', 'pem.iduser', '=', 'u_pemilik.iduser')
            ->leftJoin('role_user as ru', 'ru.idrole_user', '=', 'rm.dokter_pemeriksa')
            ->leftJoin('user as u_dokter', 'u_dokter.iduser', '=', 'ru.iduser')
            ->leftJoin('user as u_deleted', 'u_deleted.iduser', '=', 'rm.deleted_by')
            ->whereNotNull('rm.deleted_at')
            ->orderBy('rm.deleted_at', 'desc')
            ->get();

        return view('rshp.admin.DataMaster.rekam-medis.trash', compact('trashedItems'));
    }

    // RESTORE
    public function restore($id)
    {
        try {
            $updated = DB::table('rekam_medis')
                ->where('idrekam_medis', $id)
                ->update([
                    'deleted_at' => null,
                    'deleted_by' => null
                ]);

            if ($updated) {
                return redirect()->route('admin.rekam-medis.trash')
                    ->with('success', 'Rekam medis berhasil dipulihkan');
            }

            return redirect()->back()->with('error', 'Gagal memulihkan rekam medis');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan: ' . $e->getMessage());
        }
    }

    // FORCE DELETE
    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();

            DB::table('detail_rekam_medis')
                ->where('idrekam_medis', $id)
                ->delete();

            $deleted = DB::table('rekam_medis')
                ->where('idrekam_medis', $id)
                ->delete();

            DB::commit();

            if ($deleted) {
                return redirect()->route('admin.rekam-medis.trash')
                    ->with('success', 'Rekam medis berhasil dihapus permanen');
            }

            return redirect()->back()->with('error', 'Gagal menghapus permanen');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus permanen: ' . $e->getMessage());
        }
    }
}
