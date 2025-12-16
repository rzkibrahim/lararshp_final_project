<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ============================================================================
// CONTROLLER IMPORTS
// ============================================================================

// Public Controllers
use App\Http\Controllers\rshp\rshpController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\JenisHewanController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KategoriKlinisController;
use App\Http\Controllers\Admin\KodeTindakanTerapiController;
use App\Http\Controllers\Admin\RasHewanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PemilikController;
use App\Http\Controllers\Admin\PetController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\PerawatController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\TemuDokterAdminController;

// Dokter Controllers
use App\Http\Controllers\Dokter\DashboardDokterController;
use App\Http\Controllers\Dokter\RekamMedisListController;

// Perawat Controllers
use App\Http\Controllers\Perawat\DashboardPerawatController;
use App\Http\Controllers\Perawat\RekamMedisPerController;

// Pemilik Controllers
use App\Http\Controllers\Pemilik\DashboardPemilikController;
use App\Http\Controllers\Pemilik\PetListController;
use App\Http\Controllers\Pemilik\ReservasiListController;
use App\Http\Controllers\Pemilik\RekamMedisPemController;

// Resepsionis Controllers
use App\Http\Controllers\Resepsionis\DashboardResepsionisController;
use App\Http\Controllers\Resepsionis\RegistrasiPemilikController;
use App\Http\Controllers\Resepsionis\RegistrasiPetController;
use App\Http\Controllers\Resepsionis\TemuDokterController;

// ============================================================================
// PUBLIC ROUTES (Tidak memerlukan autentikasi)
// ============================================================================

// Database Connection Check
Route::get('/cek-koneksi', [rshpController::class, 'cekKoneksi'])
    ->name('site.cek-koneksi');

// Main Pages
Route::get('/', [rshpController::class, 'index'])->name('home');
Route::get('/struktur', [rshpController::class, 'struktur'])->name('struktur');
Route::get('/layanan', [rshpController::class, 'layanan'])->name('layanan');
Route::get('/visi-misi', [rshpController::class, 'visiMisi'])->name('visi-misi');

// Authentication Routes (Laravel default: login, register, password reset, etc.)
Auth::routes();

// ============================================================================
// DEBUG ROUTES (Hapus di production!)
// ============================================================================

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/')->with('success', 'Logout berhasil');
});

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return 'User sedang login: ' . Auth::user()->email;
    }
    return 'User belum login';
});

// ============================================================================
// ADMINISTRATOR ROUTES
// Middleware: isAdministrator
// Prefix: /admin
// ============================================================================

Route::middleware(['isAdministrator'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // ========================================================================
    // DATA MASTER ROUTES
    // ========================================================================
    
    Route::prefix('datamaster')->group(function () {

        // ====================================================================
        // JENIS HEWAN (Animal Species)
        // ====================================================================
        Route::prefix('jenis-hewan')->name('jenis-hewan.')->group(function () {
            // Trash Management
            Route::get('/trash', [JenisHewanController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [JenisHewanController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [JenisHewanController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('jenis-hewan', JenisHewanController::class)->except(['show']);

        // ====================================================================
        // RAS HEWAN (Animal Breeds)
        // ====================================================================
        Route::prefix('ras-hewan')->name('ras-hewan.')->group(function () {
            // Trash Management
            Route::get('/trash', [RasHewanController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [RasHewanController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [RasHewanController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('ras-hewan', RasHewanController::class)->except(['show']);

        // ====================================================================
        // KATEGORI (Categories)
        // ====================================================================
        Route::prefix('kategori')->name('kategori.')->group(function () {
            // Trash Management
            Route::get('/trash', [KategoriController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [KategoriController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [KategoriController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('kategori', KategoriController::class)->except(['show']);

        // ====================================================================
        // KATEGORI KLINIS (Clinical Categories)
        // URL: /admin/datamaster/kk
        // ====================================================================
        Route::prefix('kk')->name('kategori-klinis.')->group(function () {
            // Trash Management
            Route::get('/trash', [KategoriKlinisController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [KategoriKlinisController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [KategoriKlinisController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('kk', KategoriKlinisController::class)
            ->except(['show'])
            ->names([
                'index' => 'kategori-klinis.index',
                'create' => 'kategori-klinis.create',
                'store' => 'kategori-klinis.store',
                'edit' => 'kategori-klinis.edit',
                'update' => 'kategori-klinis.update',
                'destroy' => 'kategori-klinis.destroy',
            ]);

        // ====================================================================
        // KODE TINDAKAN TERAPI (Treatment Codes)
        // URL: /admin/datamaster/ktt
        // ====================================================================
        Route::prefix('ktt')->name('kode-tindakan-terapi.')->group(function () {
            // Trash Management
            Route::get('/trash', [KodeTindakanTerapiController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [KodeTindakanTerapiController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [KodeTindakanTerapiController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('ktt', KodeTindakanTerapiController::class)
            ->except(['show'])
            ->names([
                'index' => 'kode-tindakan-terapi.index',
                'create' => 'kode-tindakan-terapi.create',
                'store' => 'kode-tindakan-terapi.store',
                'edit' => 'kode-tindakan-terapi.edit',
                'update' => 'kode-tindakan-terapi.update',
                'destroy' => 'kode-tindakan-terapi.destroy',
            ]);

        // ====================================================================
        // ROLE (User Roles)
        // ====================================================================
        Route::prefix('role')->name('role.')->group(function () {
            // Trash Management
            Route::get('/trash', [RoleController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [RoleController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('role', RoleController::class)->except(['show']);

        // ====================================================================
        // USER (System Users)
        // ====================================================================
        Route::prefix('user')->name('user.')->group(function () {
            // Trash Management
            Route::get('/trash', [UserController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [UserController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
            
            // Reset Password
            Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('reset-password');
        });
        
        Route::resource('user', UserController::class)->except(['show']);

        // ====================================================================
        // PEMILIK (Pet Owners)
        // ====================================================================
        Route::prefix('pemilik')->name('pemilik.')->group(function () {
            // Trash Management
            Route::get('/trash', [PemilikController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [PemilikController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [PemilikController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('pemilik', PemilikController::class)->except(['show']);

        // ====================================================================
        // PET (Pets/Animals)
        // ====================================================================
        Route::prefix('pet')->name('pet.')->group(function () {
            // Trash Management
            Route::get('/trash', [PetController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [PetController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [PetController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('pet', PetController::class);

        // ====================================================================
        // DOKTER (Veterinarians)
        // ====================================================================
        Route::prefix('dokter')->name('dokter.')->group(function () {
            // Trash Management
            Route::get('/trash', [DokterController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [DokterController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [DokterController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('dokter', DokterController::class)->except(['show']);

        // ====================================================================
        // PERAWAT (Nurses)
        // ====================================================================
        Route::prefix('perawat')->name('perawat.')->group(function () {
            // Trash Management
            Route::get('/trash', [PerawatController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [PerawatController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [PerawatController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('perawat', PerawatController::class)->except(['show']);

        // ====================================================================
        // TEMU DOKTER (Doctor Appointments/Reservations)
        // ====================================================================
        Route::prefix('temu-dokter')->name('temu-dokter.')->group(function () {
            // Custom Actions
            Route::post('/update-status', [TemuDokterAdminController::class, 'updateStatus'])->name('update-status');
            
            // Trash Management
            Route::get('/trash', [TemuDokterAdminController::class, 'trash'])->name('trash');
            Route::post('/{id}/restore', [TemuDokterAdminController::class, 'restore'])->name('restore');
            Route::delete('/{id}/force-delete', [TemuDokterAdminController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('temu-dokter', TemuDokterAdminController::class)->except(['show']);

        // ====================================================================
        // REKAM MEDIS (Medical Records)
        // ====================================================================
        Route::prefix('rekam-medis')->name('rekam-medis.')->group(function () {
            // Custom Create (with reservation and pet ID)
            Route::get('/create/{idReservasi}/{idPet}', [RekamMedisController::class, 'create'])->name('create');
            
            // Detail View
            Route::get('/detail/{id}', [RekamMedisController::class, 'detail'])->name('detail');
            
            // Update Header
            Route::put('/update-header/{id}', [RekamMedisController::class, 'updateHeader'])->name('update-header');
            
            // Detail Management (Tindakan)
            Route::post('/detail/{id}/create', [RekamMedisController::class, 'createDetail'])->name('create-detail');
            Route::put('/detail/{id}/update/{idDetail}', [RekamMedisController::class, 'updateDetail'])->name('update-detail');
            Route::delete('/detail/{id}/delete/{idDetail}', [RekamMedisController::class, 'deleteDetail'])->name('delete-detail');
            
            // Trash Management
            Route::get('/trash', [RekamMedisController::class, 'trash'])->name('trash');
            Route::post('/restore/{id}', [RekamMedisController::class, 'restore'])->name('restore');
            Route::delete('/force-delete/{id}', [RekamMedisController::class, 'forceDelete'])->name('force-delete');
        });
        
        Route::resource('rekam-medis', RekamMedisController::class)->only(['index', 'store', 'destroy']);
    });
});

// ============================================================================
// RESEPSIONIS ROUTES
// Middleware: auth, isResepsionis
// Prefix: /resepsionis
// ============================================================================

Route::middleware(['auth', 'isResepsionis'])->prefix('resepsionis')->name('resepsionis.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardResepsionisController::class, 'index'])->name('dashboard');

    // ========================================================================
    // PEMILIK MANAGEMENT (Owner Registration)
    // ========================================================================
    Route::resource('pemilik', RegistrasiPemilikController::class)->except(['show']);

    // ========================================================================
    // PET MANAGEMENT (Pet Registration)
    // ========================================================================
    Route::resource('pet', RegistrasiPetController::class);

    // ========================================================================
    // TEMU DOKTER (Appointment Management)
    // ========================================================================
    Route::prefix('temu-dokter')->name('temu-dokter.')->group(function () {
        Route::get('/', [TemuDokterController::class, 'index'])->name('index');
        Route::post('/', [TemuDokterController::class, 'store'])->name('store');
        Route::post('/update-status', [TemuDokterController::class, 'updateStatus'])->name('update-status');
    });

    // ========================================================================
    // BACKWARD COMPATIBILITY ROUTES (Bisa dihapus jika tidak digunakan)
    // ========================================================================
    Route::get('/registrasi-pemilik', [RegistrasiPemilikController::class, 'index'])->name('registrasi.pemilik');
    Route::post('/registrasi-pemilik', [RegistrasiPemilikController::class, 'store'])->name('registrasi.pemilik.store');
    Route::get('/registrasi-pet', [RegistrasiPetController::class, 'index'])->name('registrasi.pet');
    Route::get('/registrasi-pet/create', [RegistrasiPetController::class, 'create'])->name('registrasi.pet.create');
    Route::post('/registrasi-pet', [RegistrasiPetController::class, 'store'])->name('registrasi.pet.store');
});

// ============================================================================
// DOKTER ROUTES
// Middleware: auth, isDokter
// Prefix: /dokter
// ============================================================================

Route::middleware(['auth', 'isDokter'])->prefix('dokter')->name('dokter.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardDokterController::class, 'index'])->name('dashboard');

    // Data Pasien (Patient List)
    Route::get('/data-pasien', [DashboardDokterController::class, 'dataPasien'])->name('data-pasien');

    // Profil
    Route::get('/profil', [DashboardDokterController::class, 'profil'])->name('profil');

    // ========================================================================
    // REKAM MEDIS (Medical Records)
    // ========================================================================
    
    // List & Detail View
    Route::get('/rekam-medis', [RekamMedisListController::class, 'index'])->name('rekam-medis.list');
    Route::get('/rekam-medis/{id}', [RekamMedisListController::class, 'show'])->name('rekam-medis.show');

    // Detail Management (Treatment Details)
    Route::prefix('rekam-medis/{idRekamMedis}/detail')->name('detail-rekam-medis.')->group(function () {
        Route::get('/create', [DashboardDokterController::class, 'detailRekamMedisCreate'])->name('create');
        Route::post('/', [DashboardDokterController::class, 'detailRekamMedisStore'])->name('store');
        Route::get('/{idDetail}/edit', [DashboardDokterController::class, 'detailRekamMedisEdit'])->name('edit');
        Route::put('/{idDetail}', [DashboardDokterController::class, 'detailRekamMedisUpdate'])->name('update');
        Route::delete('/{idDetail}', [DashboardDokterController::class, 'detailRekamMedisDestroy'])->name('destroy');
    });

    // Complete Medical Record
    Route::post('/rekam-medis/{id}/selesai', [DashboardDokterController::class, 'selesaiRekamMedis'])->name('rekam-medis.selesai');
});

// ============================================================================
// PERAWAT ROUTES
// Middleware: auth, isPerawat
// Prefix: /perawat
// ============================================================================

Route::middleware(['auth', 'isPerawat'])->prefix('perawat')->name('perawat.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardPerawatController::class, 'index'])->name('dashboard');

    // Data Pasien (Patient List)
    Route::get('/data-pasien', [DashboardPerawatController::class, 'dataPasien'])->name('data-pasien');

    // Pasien Hari Ini (Today's Patients)
    Route::get('/pasien-hari-ini', [DashboardPerawatController::class, 'pasienHariIni'])->name('pasien-hari-ini');

    // Profil
    Route::get('/profil', [DashboardPerawatController::class, 'profil'])->name('profil');

    // ========================================================================
    // REKAM MEDIS (Medical Records Management)
    // ========================================================================
    Route::prefix('rekam-medis')->name('rekam-medis.')->group(function () {
        // List
        Route::get('/', [RekamMedisPerController::class, 'index'])->name('index');
        
        // Create with reservation and pet ID
        Route::get('/create/{idReservasi}/{idPet}', [RekamMedisPerController::class, 'create'])->name('create');
        Route::post('/', [RekamMedisPerController::class, 'store'])->name('store');
        
        // Detail View
        Route::get('/{id}', [RekamMedisPerController::class, 'detail'])->name('detail');
        
        // Update Header
        Route::post('/{id}/header', [RekamMedisPerController::class, 'updateHeader'])->name('update-header');
        
        // Detail Management
        Route::post('/{id}/detail', [RekamMedisPerController::class, 'createDetail'])->name('create-detail');
        Route::put('/{id}/detail/{idDetail}', [RekamMedisPerController::class, 'updateDetail'])->name('update-detail');
        Route::delete('/{id}/detail/{idDetail}', [RekamMedisPerController::class, 'deleteDetail'])->name('delete-detail');
    });

    // ========================================================================
    // TINDAKAN (Treatment Actions)
    // ========================================================================
    Route::get('/tindakan', [DashboardPerawatController::class, 'tindakan'])->name('tindakan');
    Route::get('/tindakan/create/{id}', [DashboardPerawatController::class, 'tindakanCreate'])->name('tindakan.create');
});

// ============================================================================
// PEMILIK ROUTES (Pet Owner)
// Middleware: auth, isPemilik
// Prefix: /pemilik
// ============================================================================

Route::middleware(['auth', 'isPemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardPemilikController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardPemilikController::class, 'index'])->name('home');

    // Profil
    Route::get('/profil', [DashboardPemilikController::class, 'profil'])->name('profil');

    // ========================================================================
    // PET (View owned pets)
    // ========================================================================
    Route::get('/pet', [PetListController::class, 'index'])->name('pet.list');
    Route::get('/pet/{id}', [PetListController::class, 'show'])->name('pet.show');

    // ========================================================================
    // REKAM MEDIS (View medical records)
    // ========================================================================
    Route::get('/rekammedis', [RekamMedisPemController::class, 'index'])->name('rekammedis.list');
    Route::get('/rekammedis/{id}', [RekamMedisPemController::class, 'show'])->name('rekammedis.show');

    // ========================================================================
    // RESERVASI (Appointment Management)
    // ========================================================================
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/', [ReservasiListController::class, 'index'])->name('list');
        Route::post('/', [ReservasiListController::class, 'store'])->name('store');
        Route::post('/{id}/cancel', [ReservasiListController::class, 'cancel'])->name('cancel');
    });
});