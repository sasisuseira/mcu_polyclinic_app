<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{AuthController, BerandaController, HakaksesController, MasterdataController, FileController, PendaftaranController, ProfileController, PemeriksaanFisikController, LaboratoriumController, PoliklinikController, LaporanController, DeveloperController, DeveloperUpdateLogController};
use Illuminate\Http\Request;

Route::get('generate-csrf-token', function () { $token = csrf_token(); return response()->json(['csrf_token' => $token]); });
Route::get('403', function () { return view('error.403_error'); });
Route::get('infopembaruan', [DeveloperUpdateLogController::class, 'publicIndex'])->name('dev.update-logs.public');
Route::domain(config('app.domains.pendaftaran_mandiri'))->group(function () {
    Route::get('/', [PendaftaranController::class, "formulir_pendaftaran"])->name('landing.formulir_pendaftaran');
    Route::get('no_antrian/{kode_antrian}', [PendaftaranController::class, "formulir_no_antrian"])->name('landing.formulir_no_antrian'); 
});
Route::get('/', function (Request $req) {
    $domain = $req->getHost();
    if ($domain === config('app.domains.pendaftaran_artha')) {
        $data = ['tipe_halaman' => 'login_perusahaan'];
        return view('login_perusahaan', ['data' => $data]);
    }
    $data = ['tipe_halaman' => 'login'];
    return view('login', ['data' => $data]);
})->name('login');
Route::get('laporan/validasi_berkas_mcu', [LaporanController::class,"validasi_berkas_mcu"])->name('admin.laporan.validasi_berkas_mcu');
Route::group(['middleware' => ['jwt.cookie']], function () {
    Route::get('pintukeluar', [AuthController::class, "logout"]);
    Route::prefix('akun')->group(function () {
        Route::get('profile', [ProfileController::class,"profile"])->name('admin.akun.profile');
    });
    Route::prefix('dev')->group(function () {
        Route::get('error_log_app', [DeveloperController::class,"error_log"])->middleware('permission_cache:akses_error_log')->name('dev.error_log');
        Route::get('update_logs', [DeveloperUpdateLogController::class, 'index'])->middleware('permission_cache:akses_update_log')->name('dev.update-logs.index');
        Route::post('update_logs', [DeveloperUpdateLogController::class, 'store'])->middleware('permission_cache:akses_update_log')->name('dev.update-logs.store');
        Route::put('update_logs/{updateLog}', [DeveloperUpdateLogController::class, 'update'])->middleware('permission_cache:akses_update_log')->name('dev.update-logs.update');
        Route::delete('update_logs/{updateLog}', [DeveloperUpdateLogController::class, 'destroy'])->middleware('permission_cache:akses_update_log')->name('dev.update-logs.destroy');
    });
    Route::prefix('admin')->group(function () {
        Route::get('beranda', [BerandaController::class,"index"])->middleware('permission_cache:akses_beranda')->name('admin.beranda');
        Route::get('antrian', [BerandaController::class,"antrian"])->middleware('permission_cache:akses_antrian')->name('admin.antrian');
        Route::get('kasir', [BerandaController::class,"kasir"])->middleware('permission_cache:akses_kasir')->name('admin.kasir');
        Route::get('pengguna_aplikasi', [HakaksesController::class,"pengguna_aplikasi"])->middleware('permission_cache:akses_petugas')->name('admin.pengguna_aplikasi');
        Route::get('permission', [HakaksesController::class,"permission"])->middleware('permission_cache:akses_hak_permission')->name('admin.permission');
        Route::get('role', [HakaksesController::class,"role"])->middleware('permission_cache:akses_petugas')->name('admin.role');
    });
    Route::prefix('pendaftaran')->group(function () {
        Route::get('daftar_peserta', [PendaftaranController::class,"list_peserta"])->middleware('permission_cache:akses_pendaftaran_daftar_peserta')->name('admin.pendaftaran.daftar_peserta');
        Route::get('daftar_pasien', [PendaftaranController::class,"list_pasien"])->middleware('permission_cache:akses_pendaftaran_daftar_pasien')->name('admin.pendaftaran.daftar_pasien');
        Route::get('formulir_tambah_peserta/{nomor_identifikasi?}', [PendaftaranController::class,"add_form_patien_mcu"])->middleware('permission_cache:akses_pendaftaran_daftar_peserta')->name('admin.pendaftaran.formulir_tambah_peserta');
        Route::get('formulir_ubah_peserta/{uuid?}', [PendaftaranController::class,"update_form_patien_mcu"])->middleware('permission_cache:akses_pendaftaran_daftar_peserta')->name('admin.pendaftaran.formulir_ubah_peserta');
        /* Riwayat Informasi */
        Route::get('foto_pasien', [PendaftaranController::class,"foto_pasien"])->middleware('permission_cache:akses_pendaftaran_foto_pasien')->name('admin.pendaftaran.foto_pasien');
        Route::get('lingkungan_kerja', [PendaftaranController::class,"lingkungan_kerja"])->middleware('permission_cache:akses_pendaftaran_lingkungan_kerja')->name('admin.pendaftaran.lingkungan_kerja');
        Route::get('kecelakaan_kerja', [PendaftaranController::class,"kecelakaan_kerja"])->middleware('permission_cache:akses_pendaftaran_kecelakaan_kerja')->name('admin.pendaftaran.kecelakaan_kerja');
        Route::get('kebiasaan_hidup', [PendaftaranController::class,"kebiasaan_hidup"])->middleware('permission_cache:akses_pendaftaran_kebiasaan_hidup')->name('admin.pendaftaran.kebiasaan_hidup');
        Route::get('penyakit_terdahulu', [PendaftaranController::class,"penyakit_terdahulu"])->middleware('permission_cache:akses_pendaftaran_penyakit_terdahulu')->name('admin.pendaftaran.penyakit_terdahulu');
        Route::get('penyakit_keluarga', [PendaftaranController::class,"penyakit_keluarga"])->middleware('permission_cache:akses_pendaftaran_penyakit_keluarga')->name('admin.pendaftaran.penyakit_keluarga');
        Route::get('imunisasi', [PendaftaranController::class,"imunisasi"])->middleware('permission_cache:akses_pendaftaran_imunisasi')->name('admin.pendaftaran.imunisasi');
    });
    Route::prefix('pemeriksaan_fisik')->group(function () {
        Route::get('tingkat_kesadaran', [PemeriksaanFisikController::class,"tingkat_kesadaran"])->middleware('permission_cache:akses_pemeriksaan_fisik_tingkat_kesadaran')->name('admin.pemeriksaan_fisik.tingkat_kesadaran');
        Route::get('tanda_vital', [PemeriksaanFisikController::class,"tanda_vital"])->middleware('permission_cache:akses_pemeriksaan_fisik_tanda_vital')->name('admin.pemeriksaan_fisik.tanda_vital');
        Route::get('penglihatan', [PemeriksaanFisikController::class,"penglihatan"])->middleware('permission_cache:akses_pemeriksaan_fisik_penglihatan')->name('admin.pemeriksaan_fisik.penglihatan');
        Route::get('kondisi_fisik/{lokasi_fisik}', [PemeriksaanFisikController::class,"kondisi_fisik"])->middleware('permission_cache:akses_pemeriksaan_fisik_kondisi_fisik')->name('admin.pemeriksaan_fisik.kondisi_fisik');
    });
    Route::get('poli/{jenis_poli}', [PoliklinikController::class,"poliklinik"])->middleware('permission_cache:akses_poliklinik,akses_spirometri,akses_audiometri,akses_ekg,akses_threadmill,akses_rontgen_thorax,akses_rontgen_lumbosacral,akses_usg_ubdomain,akses_farmingham_score')->name('admin.poliklinik');
    Route::prefix('masterdata')->middleware('permission_cache:akses_master_data')->group(function () {
        Route::get('daftar_perusahaan', [MasterdataController::class,"daftar_perusahaan"])->middleware('permission_cache:akses_master_perusahaan')->name('admin.masterdata.daftar_perusahaan');
        Route::get('daftar_paket_mcu', [MasterdataController::class,"daftar_paket_mcu"])->middleware('permission_cache:akses_paket_harga')->name('admin.masterdata.daftar_paket_mcu');
        Route::get('daftar_jasa_pelayanan', [MasterdataController::class,"daftar_jasa_pelayanan"])->middleware('permission_cache:akses_jasa_pelayanan')->name('admin.masterdata.daftar_jasa_pelayanan');
        Route::get('daftar_departemen_peserta', [MasterdataController::class,"daftar_departemen_peserta"])->middleware('permission_cache:akses_departemen_peserta')->name('admin.masterdata.daftar_departemen_peserta');
        Route::get('daftar_member_mcu', [MasterdataController::class,"daftar_member_mcu"])->middleware('permission_cache:akses_member_mcu')->name('admin.masterdata.daftar_member_mcu');
        Route::get('daftar_partner_amc', [MasterdataController::class,"daftar_partner_amc"])->middleware('permission_cache:akses_partner_amc')->name('admin.masterdata.daftar_partner_amc');
        Route::get('daftar_bank', [MasterdataController::class,"daftar_bank"])->middleware('permission_cache:akses_daftar_bank')->name('admin.masterdata.daftar_bank');
        Route::get('daftar_kesimpulan', [MasterdataController::class,"daftar_kesimpulan"])->middleware('permission_cache:akses_daftar_kesimpulan')->name('admin.masterdata.daftar_kesimpulan');
    });
    Route::prefix('laboratorium')->group(function () {
        Route::get('tarif', [LaboratoriumController::class,"tarif"])->middleware('permission_cache:akses_tarif_laboratorium')->name('admin.laboratorium.tarif');
        Route::get('kategori', [LaboratoriumController::class,"kategori"])->middleware('permission_cache:akses_kategori_laboratorium')->name('admin.laboratorium.kategori');
        Route::get('satuan', [LaboratoriumController::class,"satuan"])->middleware('permission_cache:akses_satuan_laboratorium')->name('admin.laboratorium.satuan');
        Route::get('rentang_kenormalan', [LaboratoriumController::class,"rentang_kenormalan"])->middleware('permission_cache:akses_rentang_kenormalan_laboratorium')->name('admin.laboratorium.rentang_kenormalan');
        Route::get('templating', [LaboratoriumController::class,"templating"])->middleware('permission_cache:akses_templating_laboratorium')->name('admin.laboratorium.templating');
        /* tindakan laboratorium */
        Route::get('daftar_tindakan', [LaboratoriumController::class,"daftar_tindakan"])->middleware('permission_cache:akses_tindakan_laboratorium')->name('admin.laboratorium.daftar_tindakan');
        Route::get('tindakan', [LaboratoriumController::class,"tindakan"])->middleware('permission_cache:akses_tindakan_laboratorium')->name('admin.laboratorium.tindakan');
    });
    Route::prefix('image')->group(function () {
        Route::get('user/signature/{filename}', [FileController::class, 'showSignature']);
    });
    Route::prefix('laporan')->group(function () {
        Route::get('validasi_mcu', [LaporanController::class,"validasi_mcu"])->middleware('permission_cache:akses_validasi_mcu')->name('admin.laporan.validasi_mcu');
        Route::get('validasi_mcu/nota/{no_nota}', [LaporanController::class,"validasi_mcu_nota"])->middleware('permission_cache:akses_validasi_mcu')->name('admin.laporan.validasi_mcu_nota');
        Route::get('validasi_rekap_kesimpulan', [LaporanController::class,"validasi_rekap_kesimpulan"])->middleware('permission_cache:akses_validasi_kesimpulan')->name('admin.laporan.validasi_rekap_kesimpulan');
        Route::prefix('berkas')->group(function () {
            Route::get('mcu',[LaporanController::class,"berkas_mcu"])->middleware('permission_cache:akses_berkas_tindakan_mcu')->name('admin.laporan.berkas_mcu');
            Route::get('mcu_threadmill',[LaporanController::class,"berkas_mcu_threadmill"])->middleware('permission_cache:akses_berkas_tindakan_threadmill')->name('admin.laporan.berkas_mcu_threadmill');
            Route::get('laboratorium',[LaporanController::class,"berkas_laboratorium"])->middleware('permission_cache:akses_berkas_tindakan_laboratorium')->name('admin.laporan.berkas_laboratorium');
            Route::get('kuitansi',[LaporanController::class,"berkas_kuitansi"])->middleware('permission_cache:akses_berkas_tindakan_kuitansi')->name('admin.laporan.berkas_kuitansi');
            Route::get('mcu/cetak',[LaporanController::class,"cetak_berkas_mcu"])->middleware('permission_cache:akses_berkas_tindakan_mcu')->name('admin.laporan.cetak_berkas_mcu');
            Route::get('mcu_threadmill/cetak',[LaporanController::class,"cetak_berkas_mcu_threadmill"])->middleware('permission_cache:akses_berkas_tindakan_mcu')->name('admin.laporan.cetak_berkas_mcu_threadmill');
            Route::get('mcu/cetak_laboratorium',[LaporanController::class,"cetak_berkas_laboratorium"])->middleware('permission_cache:akses_berkas_tindakan_laboratorium')->name('admin.laporan.cetak_berkas_laboratorium');
        });
        Route::prefix('berkas_perusahaan')->group(function () {
            Route::get('mcu',[LaporanController::class,"berkas_mcu"])->middleware('permission_cache:akses_berkas_tindakan_mcu_perusahaan')->name('admin.laporan.berkas_mcu_perusahaan');
            Route::get('threadmill',[LaporanController::class,"berkas_mcu_threadmill"])->middleware('permission_cache:akses_berkas_tindakan_threadmill')->name('admin.laporan.berkas_mcu_threadmill');
            Route::get('laboratorium',[LaporanController::class,"berkas_laboratorium"])->middleware('permission_cache:akses_berkas_tindakan_laboratorium')->name('admin.laporan.berkas_laboratorium');
            Route::get('kuitansi',[LaporanController::class,"laporan_kuitansi"])->middleware('permission_cache:akses_laporan_kuitansi')->name('admin.laporan.laporan_kuitansi');
        });
        Route::prefix('rekap')->group(function () {
            Route::get('pemeriksaan_fisik',[LaporanController::class,"laporan_rekap_pemeriksaan_fisik"])->middleware('permission_cache:akses_laporan_rekap_pemeriksaan_fisik')->name('admin.laporan.laporan_rekap_pemeriksaan_fisik');
            Route::get('vital',[LaporanController::class,"laporan_rekap_vital"])->middleware('permission_cache:akses_laporan_rekap_vital')->name('admin.laporan.laporan_rekap_vital');
            Route::get('spirometri',[LaporanController::class,"laporan_rekap_spirometri"])->middleware('permission_cache:akses_laporan_rekap_spirometri')->name('admin.laporan.laporan_rekap_spirometri');
            Route::get('audiometri',[LaporanController::class,"laporan_rekap_audiometri"])->middleware('permission_cache:akses_laporan_rekap_audiometri')->name('admin.laporan.laporan_rekap_audiometri');
            Route::get('ekg',[LaporanController::class,"laporan_rekap_ekg"])->middleware('permission_cache:akses_laporan_rekap_ekg')->name('admin.laporan.laporan_rekap_ekg');
            Route::get('threadmill',[LaporanController::class,"laporan_rekap_threadmill"])->middleware('permission_cache:akses_laporan_rekap_threadmill')->name('admin.laporan.laporan_rekap_threadmill');
            Route::get('rontgen_thorax',[LaporanController::class,"laporan_rekap_rontgen_thorax"])->middleware('permission_cache:akses_laporan_rekap_rontgen_thorax')->name('admin.laporan.laporan_rekap_rontgen_thorax');
            Route::get('rontgen_lumbosacral',[LaporanController::class,"laporan_rekap_rontgen_lumbosacral"])->middleware('permission_cache:akses_laporan_rekap_rontgen_lumbosacral')->name('admin.laporan.laporan_rekap_rontgen_lumbosacral');
            Route::get('usg_ubdomain',[LaporanController::class,"laporan_rekap_usg_ubdomain"])->middleware('permission_cache:akses_laporan_rekap_usg_ubdomain')->name('admin.laporan.laporan_rekap_usg_ubdomain');
            Route::get('farmingham_score',[LaporanController::class,"laporan_rekap_farmingham_score"])->middleware('permission_cache:akses_laporan_rekap_farmingham_score')->name('admin.laporan.laporan_rekap_farmingham_score');
        });
        Route::prefix('rekap_perusahaan')->group(function () {
            Route::get('pemeriksaan_fisik',[LaporanController::class,"laporan_rekap_pemeriksaan_fisik"])->middleware('permission_cache:akses_rekap_pemeriksaan_fisik_perusahaan')->name('admin.laporan.laporan_rekap_pemeriksaan_fisik');
            Route::get('vital',[LaporanController::class,"laporan_rekap_vital"])->middleware('permission_cache:akses_rekap_vital_perusahaan')->name('admin.laporan.laporan_rekap_vital');
            Route::get('spirometri',[LaporanController::class,"laporan_rekap_spirometri"])->middleware('permission_cache:akses_rekap_spirometri_perusahaan')->name('admin.laporan.laporan_rekap_spirometri');
            Route::get('audiometri',[LaporanController::class,"laporan_rekap_audiometri"])->middleware('permission_cache:akses_rekap_audiometri_perusahaan')->name('admin.laporan.laporan_rekap_audiometri');
            Route::get('ekg',[LaporanController::class,"laporan_rekap_ekg"])->middleware('permission_cache:akses_rekap_ekg_perusahaan')->name('admin.laporan.laporan_rekap_ekg');
            Route::get('threadmill',[LaporanController::class,"laporan_rekap_threadmill"])->middleware('permission_cache:akses_rekap_threadmill_perusahaan')->name('admin.laporan.laporan_rekap_threadmill');
            Route::get('rontgen_thorax',[LaporanController::class,"laporan_rekap_rontgen_thorax"])->middleware('permission_cache:akses_rekap_rontgen_thorax_perusahaan')->name('admin.laporan.laporan_rekap_rontgen_thorax');
            Route::get('rontgen_lumbosacral',[LaporanController::class,"laporan_rekap_rontgen_lumbosacral"])->middleware('permission_cache:akses_rekap_rontgen_lumbosacral_perusahaan')->name('admin.laporan.laporan_rekap_rontgen_lumbosacral');
            Route::get('usg_ubdomain',[LaporanController::class,"laporan_rekap_usg_ubdomain"])->middleware('permission_cache:akses_rekap_usg_ubdomain_perusahaan')->name('admin.laporan.laporan_rekap_usg_ubdomain');
            Route::get('farmingham_score',[LaporanController::class,"laporan_rekap_farmingham_score"])->middleware('permission_cache:akses_rekap_farmingham_score_perusahaan')->name('admin.laporan.laporan_rekap_farmingham_score');
        });
        Route::prefix('kuitansi')->group(function () {
            Route::get('personal/cetak',[LaporanController::class,"cetak_kuitansi_personal"])->middleware('permission_cache:akses_berkas_tindakan_personal')->name('admin.laporan.berkas_personal');
            Route::get('perusahaan/cetak',[LaporanController::class,"cetak_kuitansi_perusahaan"])->middleware('permission_cache:akses_berkas_tindakan_perusahaan')->name('admin.laporan.berkas_perusahaan');
            Route::get('tagihan_perusahaan/cetak',[LaporanController::class,"cetak_kuitansi_tagihan_perusahaan"])->middleware('permission_cache:akses_berkas_tindakan_tagihan_perusahaan')->name('admin.laporan.berkas_tagihan_perusahaan');
        });
        Route::prefix('transaksi')->group(function () {
            Route::get('penjualan',[LaporanController::class,"laporan_penjualan"])->middleware('permission_cache:akses_laporan_penjualan')->name('admin.laporan.laporan_penjualan');
            Route::get('kuitansi',[LaporanController::class,"laporan_kuitansi"])->middleware('permission_cache:akses_laporan_kuitansi')->name('admin.laporan.laporan_kuitansi');
            Route::get('insentif',[LaporanController::class,"laporan_insentif"])->middleware('permission_cache:akses_laporan_insentif')->name('admin.laporan.laporan_insentif');
        });
    });
});