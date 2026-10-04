<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $userId = $request->attributes->get('user_id') ?? session('user_id');
        $userPermissions = session('user_permissions_' . $userId, []);
        $userPermissionNames = collect($userPermissions)->map(function ($permissionItem) {
            if (is_object($permissionItem)) {
                return $permissionItem->name ?? null;
            }
            if (is_array($permissionItem)) {
                return $permissionItem['name'] ?? null;
            }
            return $permissionItem;
        })->filter()->values()->all();

        $isSuperAdmin = in_array('super_admin', $userPermissionNames, true);

        $requiredPermissions = array_filter(array_map('trim', explode(',', $permission)), fn ($item) => $item !== '');
        $hasAccess = $isSuperAdmin || collect($requiredPermissions)->contains(fn ($neededPermission) => in_array($neededPermission, $userPermissionNames, true));

        if (!$hasAccess) {
            return abort(403, 'Unauthorized');
        }

        $permissionsToCheck = [
            'hasAccessBeranda' => 'akses_beranda',
            'hasAccessKasir' => 'akses_kasir',
            'hasAccessAntrian' => 'akses_antrian',
            /* Pendaftaran */
            'hasAccessPendaftaran' => 'akses_pendaftaran',
            'hasAccessPendaftaranDaftarPeserta' => 'akses_pendaftaran_daftar_peserta',
            'hasAccessPendaftaranDaftarPasien' => 'akses_pendaftaran_daftar_pasien',
            /* Riwayat Informasi */
            'hasAccessPendaftaranFotoPasien' => 'akses_pendaftaran_foto_pasien',
            'hasAccessPendaftaranLingkunganKerja' => 'akses_pendaftaran_lingkungan_kerja',
            'hasAccessPendaftaranKecelakaanKerja' => 'akses_pendaftaran_kecelakaan_kerja',
            'hasAccessPendaftaranKebiasaanHidup' => 'akses_pendaftaran_kebiasaan_hidup',
            'hasAccessPendaftaranPenyakitTerdahulu' => 'akses_pendaftaran_penyakit_terdahulu',
            'hasAccessPendaftaranPenyakitKeluarga' => 'akses_pendaftaran_penyakit_keluarga',
            'hasAccessPendaftaranImunisasi' => 'akses_pendaftaran_imunisasi',
            /* Pemeriksaan Fisik */
            'hasAccessTingkatKesadaran' => 'akses_pemeriksaan_fisik_tingkat_kesadaran',
            'hasAccessTandaVital' => 'akses_pemeriksaan_fisik_tanda_vital',
            'hasAccessPenglihatan' => 'akses_pemeriksaan_fisik_penglihatan',
            'hasAccessKondisiFisikKulit' => 'akses_pemeriksaan_fisik_kondisi_fisik_kulit',
            'hasAccessKondisiFisikKepala' => 'akses_pemeriksaan_fisik_kondisi_fisik_kepala',
            'hasAccessKondisiFisikTelinga' => 'akses_pemeriksaan_fisik_kondisi_fisik_telinga',
            'hasAccessKondisiFisikMata' => 'akses_pemeriksaan_fisik_kondisi_fisik_mata',
            'hasAccessKondisiFisikTenggorokan' => 'akses_pemeriksaan_fisik_kondisi_fisik_tenggorokan',
            'hasAccessKondisiFisikMulut' => 'akses_pemeriksaan_fisik_kondisi_fisik_mulut',
            'hasAccessKondisiFisikGigi' => 'akses_pemeriksaan_fisik_kondisi_fisik_gigi',
            'hasAccessKondisiFisikLeher' => 'akses_pemeriksaan_fisik_kondisi_fisik_leher',
            'hasAccessKondisiFisikThorax' => 'akses_pemeriksaan_fisik_kondisi_fisik_thorax',
            'hasAccessKondisiFisikAbdomenUrogenital' => 'akses_pemeriksaan_fisik_kondisi_fisik_abdomen_urogenital',
            'hasAccessKondisiFisikAnorectalGenital' => 'akses_pemeriksaan_fisik_kondisi_fisik_anorectal_genital',
            'hasAccessKondisiFisikEkstremitas' => 'akses_pemeriksaan_fisik_kondisi_fisik_ekstremitas',
            'hasAccessKondisiFisikNeurologis' => 'akses_pemeriksaan_fisik_kondisi_fisik_neurologis',
            /* Poliklinik */
            'hasAccessSpirometri' => 'akses_spirometri',
            'hasAccessAudiometri' => 'akses_audiometri',
            'hasAccessEkg' => 'akses_ekg',
            'hasAccessThreadmill' => 'akses_threadmill',
            'hasAccessRontgenThorax' => 'akses_rontgen_thorax',
            'hasAccessRontgenLumbosacral' => 'akses_rontgen_lumbosacral',
            'hasAccessUSGUbdomain' => 'akses_usg_ubdomain',
            'hasAccessFarminghamScore' => 'akses_farmingham_score',
            'hasAccessInputKesimpulan' => 'akses_input_kesimpulan',
            /* Laboratorium */
            'hasAccessTarifLaboratorium' => 'akses_tarif_laboratorium',
            'hasAccessKategoriLaboratorium' => 'akses_kategori_laboratorium',
            'hasAccessSatuanLaboratorium' => 'akses_satuan_laboratorium',
            'hasAccessRentangKenormalanLaboratorium' => 'akses_rentang_kenormalan_laboratorium',
            'hasAccessRentangTemplating' => 'akses_rentang_templating',
            /* Pemeriksaan */
            'hasAccessTindakanLaboratorium' => 'akses_tindakan_laboratorium',
            /* Laporan */
            'hasAccessValidasiKesimpulan' => 'akses_validasi_kesimpulan',
            'hasAccessValidasiMcu' => 'akses_validasi_mcu',
            'hasAccessArciveMCU' => 'akses_berkas_tindakan_mcu',
            'hasAccessArciveMCUThreadmill' => 'akses_berkas_tindakan_threadmill',
            'hasAccessArciveLaboratorium' => 'akses_berkas_tindakan_laboratorium',
            'hasAccessLaporanPenjualan' => 'akses_laporan_penjualan',
            'hasAccessLaporanKuitansi' => 'akses_berkas_tindakan_kwitansi',
            'hasAccessLaporanInsentif' => 'akses_laporan_insentif',
            'hasAccessHasilKesimpulan' => 'akses_hasil_kesimpulan',
            'hasAccessRekapPemeriksaanFisik' => 'akses_rekap_pemeriksaan_fisik',
            'hasAccessRekapVital' => 'akses_rekap_vital',
            'hasAccessRekapSpirometri' => 'akses_rekap_spirometri',
            'hasAccessRekapAudiometri' => 'akses_rekap_audiometri',
            'hasAccessRekapEKG' => 'akses_rekap_ekg',
            'hasAccessRekapThreadmill' => 'akses_rekap_threadmill',
            'hasAccessRekapRontgenThorax' => 'akses_rekap_rontgen_thorax',
            'hasAccessRekapRontgenLumbosacral' => 'akses_rekap_rontgen_lumbosacral',
            'hasAccessRekapUSGUbdomain' => 'akses_rekap_usg_ubdomain',
            'hasAccessRekapFarminghamScore' => 'akses_rekap_farmingham_score',
            /* Master Data */
            'hasAccessMasterData' => 'akses_master_data',
            'hasAccessMasterPerusahaan' => 'akses_master_perusahaan',
            'hasAccessPaketHarga' => 'akses_paket_harga',
            'hasAccessJasaPelayanan' => 'akses_jasa_pelayanan',
            'hasAccessDepartemenPeserta' => 'akses_departemen_peserta',
            'hasAccessMemberMcu' => 'akses_member_mcu',
            'hasAccessPartnerAMC' => 'akses_partner_amc',
            'hasAccessDaftarBank' => 'akses_daftar_bank',
            'hasAccessMasterKesimpulan' => 'akses_daftar_kesimpulan',
            /* Petugas */
            'hasAccessPetugas' => 'akses_petugas',
            'hasAccessPenggunaAplikasi' => 'akses_pengguna_aplikasi',
            'hasAccessHakAkses' => 'akses_hak_akses',
            'hasAccessPermission' => 'akses_hak_permission',
            /* Developer Area */
            'hasAccessErrorLog' => 'akses_error_log',
            'hasAccessUpdateLog' => 'akses_update_log',
            'hasVisiblePrice' => 'akses_informasi_harga_kasir',
            /* Perusahaan Area */
            'hasAccessArciveMCUPerusahaan' => 'akses_berkas_tindakan_mcu_perusahaan',
            'hasAccessArciveMCUThreadmillPerusahaan' => 'akses_berkas_tindakan_threadmill_perusahaan',
            'hasAccessArciveLaboratoriumPerusahaan' => 'akses_berkas_tindakan_laboratorium_perusahaan',
            'hasAccessLaporanKuitansiPerusahaan' => 'akses_berkas_tindakan_kwitansi_perusahaan',
            'hasAccessRekapPemeriksaanFisikPerusahaan' => 'akses_rekap_pemeriksaan_fisik_perusahaan',
            'hasAccessRekapVitalPerusahaan' => 'akses_rekap_vital_perusahaan',
            'hasAccessRekapSpirometriPerusahaan' => 'akses_rekap_spirometri_perusahaan',
            'hasAccessRekapAudiometriPerusahaan' => 'akses_rekap_audiometri_perusahaan',
            'hasAccessRekapEKGPerusahaan' => 'akses_rekap_ekg_perusahaan',
            'hasAccessRekapThreadmillPerusahaan' => 'akses_rekap_threadmill_perusahaan',
            'hasAccessRekapRontgenThoraxPerusahaan' => 'akses_rekap_rontgen_thorax_perusahaan',
            'hasAccessRekapRontgenLumbosacralPerusahaan' => 'akses_rekap_rontgen_lumbosacral_perusahaan',
            'hasAccessRekapUSGUbdomainPerusahaan' => 'akses_rekap_usg_ubdomain_perusahaan',
            'hasAccessRekapFarminghamScorePerusahaan' => 'akses_rekap_farmingham_score_perusahaan',
        ];

        $permissionsShared = [];
        foreach ($permissionsToCheck as $key => $permissionName) {
            $permissionsShared[$key] = $this->hasPermission($userPermissionNames, $permissionName, $isSuperAdmin);
        }
        view()->share($permissionsShared);
        return $next($request);
    }

    function hasPermission($permissions, $permissionName, $isSuperAdmin) {
        if ($isSuperAdmin) {
            return true;
        }
        return in_array($permissionName, (array) $permissions, true);
    }
}
