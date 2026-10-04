<div class="sidebar-wrapper" id="sidebar-wrapper" data-layout="stroke-svg" data-state="default">
  <div>
    <div class="logo-wrapper">
      <a href="{{ url('/admin/beranda') }}">
        <img class="img-fluid" src="{{asset('mofi/assets/images/logo/Logo_AMC_Full.png')}}" alt="Logo MCU {{ config('app.name') }}" style="height: 100%;width: 75%;">
      </a>
      <div class="back-btn"><i class="fa fa-angle-left"></i></div>
      <div class="toggle-sidebar">
        <svg class="stroke-icon sidebar-toggle status_toggle middle">
          <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#toggle-icon')}}"></use>
        </svg>
        <svg class="fill-icon sidebar-toggle status_toggle middle">
          <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#fill-toggle-icon')}}"></use>
        </svg>
      </div>
    </div>
    <div class="logo-icon-wrapper">
      <a href="{{url('/admin/beranda')}}"><img class="img-fluid" style="height: 32px;width: 32px;" src="{{ asset('mofi/assets/images/logo/logo_amc.png')}}" alt=""></a>
    </div>
    <nav class="sidebar-main">
      <div class="left-arrow" id="left-arrow">
        <i data-feather="arrow-left"></i>
      </div>
      <div id="sidebar-menu">
        <ul class="sidebar-links" id="simple-bar">
          <li class="back-btn">
            <a href="index.html">
              <img class="img-fluid" src="{{ asset('mofi/assets/images/logo/logo-icon.png') }}" alt="">
            </a>
            <div class="mobile-back text-end">
              <span>Back</span>
              <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
            </div>
          </li>
    
          <!-- Pinned Section -->
          <li class="pin-title sidebar-main-title">
            <div>
              <h6>Pinned</h6>
            </div>
          </li>
          <li class="sidebar-main-title">
            <div>
              <h6>Fitur Aplikasi</h6>
            </div>
          </li>
          @if ($hasAccessBeranda)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.beranda') }}">
              <i class="fa-solid fa-house" style="padding-right: 8px;font-size: 18px;color: #fff;"></i>
              <span>Beranda</span>
            </a>
          </li>
          @endif
          @if ($hasAccessKasir)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.kasir') }}">
              <i class="fa-solid fa-cash-register" style="padding-right: 10px;font-size: 18px;color: #fff;"></i>
              <span>Konfirmasi Bayar</span>
            </a>
          </li>
          @endif
          @if ($hasAccessAntrian)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.antrian') }}">
              <i class="fa-solid fa-list-check" style="padding-right: 8px;font-size: 18px;color: #fff;"></i>
              <span>Antrian</span>
            </a>
          </li>
          @endif
          <!-- Medical Check Up Section -->
          @if ($hasAccessPendaftaran)
          <li class="sidebar-main-title">
            <div>
              <h6>Medical Check Up</h6>
            </div>
          </li>
          @endif
          @if ($hasAccessPendaftaran)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-book-open" style="padding-right: 5px;font-size: 18px;color: #fff;"></i> <span>Pendaftaran</span></a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessPendaftaranDaftarPeserta, 'url' => 'pendaftaran/daftar_peserta', 'label' => 'Daftar Peserta'],
                ['condition' => $hasAccessPendaftaranDaftarPasien, 'url' => 'pendaftaran/daftar_pasien', 'label' => 'Daftar Pasien']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
              <li>
                <a class="submenu-title" href="javascript:void(0)">
                  Riwayat Informasi
                  <div class="according-menu">
                    <i class="fa fa-angle-right"></i>
                  </div>
                </a>
                <ul class="nav-sub-childmenu submenu-content">
                  @foreach([
                      ['condition' => $hasAccessPendaftaranFotoPasien, 'url' => 'pendaftaran/foto_pasien', 'label' => 'Foto Data Diri'],
                      ['condition' => $hasAccessPendaftaranLingkunganKerja, 'url' => 'pendaftaran/lingkungan_kerja', 'label' => 'Lingkungan Kerja'],
                      ['condition' => $hasAccessPendaftaranKecelakaanKerja, 'url' => 'pendaftaran/kecelakaan_kerja', 'label' => 'Kecelakaan Kerja'],
                      ['condition' => $hasAccessPendaftaranKebiasaanHidup, 'url' => 'pendaftaran/kebiasaan_hidup', 'label' => 'Kebiasaan Hidup'],
                      ['condition' => $hasAccessPendaftaranPenyakitKeluarga, 'url' => 'pendaftaran/penyakit_keluarga', 'label' => 'Penyakit Keluarga'],
                      ['condition' => $hasAccessPendaftaranImunisasi, 'url' => 'pendaftaran/imunisasi', 'label' => 'Imunisasi']
                  ] as $menuItem)
                      @if ($menuItem['condition'])
                          <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                      @endif
                  @endforeach
              </ul>
              </li>
            </ul>
          </li>
          @endif
          @if ($hasAccessTingkatKesadaran || $hasAccessPenglihatan || $hasAccessKondisiFisikKulit || $hasAccessKondisiFisikKepala || $hasAccessKondisiFisikTelinga || $hasAccessKondisiFisikMata || $hasAccessKondisiFisikTenggorokan || $hasAccessKondisiFisikMulut || $hasAccessKondisiFisikGigi || $hasAccessKondisiFisikLeher || $hasAccessKondisiFisikThorax || $hasAccessKondisiFisikAbdomenUrogenital || $hasAccessKondisiFisikAnorectalGenital || $hasAccessKondisiFisikEkstremitas || $hasAccessKondisiFisikNeurologis || $hasAccessTarifLaboratorium || $hasAccessKategoriLaboratorium || $hasAccessSatuanLaboratorium || $hasAccessRentangKenormalanLaboratorium || $hasAccessRentangTemplating)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-stethoscope" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Poli Dokter</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessTingkatKesadaran, 'url' => 'pemeriksaan_fisik/tingkat_kesadaran', 'label' => 'Tingkat Kesadaran'],
                ['condition' => $hasAccessPendaftaranPenyakitTerdahulu, 'url' => 'pendaftaran/penyakit_terdahulu', 'label' => 'Penyakit Terdahulu'],
                ['condition' => $hasAccessPenglihatan, 'url' => 'pemeriksaan_fisik/penglihatan', 'label' => 'Penglihatan']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
              <li>
                <a class="submenu-title" href="javascript:void(0)">
                  Kondisi Fisik
                  <div class="according-menu">
                    <i class="fa fa-angle-right"></i>
                  </div>
                </a>
                <ul class="nav-sub-childmenu submenu-content">
                  @foreach([
                      ['condition' => $hasAccessKondisiFisikKulit, 'url' => 'pemeriksaan_fisik/kondisi_fisik/kulit', 'label' => 'Kulit'],
                      ['condition' => $hasAccessKondisiFisikKepala, 'url' => 'pemeriksaan_fisik/kondisi_fisik/kepala', 'label' => 'Kepala'],
                      ['condition' => $hasAccessKondisiFisikTelinga, 'url' => 'pemeriksaan_fisik/kondisi_fisik/telinga', 'label' => 'Telinga'],
                      ['condition' => $hasAccessKondisiFisikMata, 'url' => 'pemeriksaan_fisik/kondisi_fisik/mata', 'label' => 'Mata'],
                      ['condition' => $hasAccessKondisiFisikTenggorokan, 'url' => 'pemeriksaan_fisik/kondisi_fisik/tenggorokan', 'label' => 'Tenggorokan'],
                      ['condition' => $hasAccessKondisiFisikMulut, 'url' => 'pemeriksaan_fisik/kondisi_fisik/mulut', 'label' => 'Mulut'],
                      ['condition' => $hasAccessKondisiFisikGigi, 'url' => 'pemeriksaan_fisik/kondisi_fisik/gigi', 'label' => 'Gigi'],
                      ['condition' => $hasAccessKondisiFisikLeher, 'url' => 'pemeriksaan_fisik/kondisi_fisik/leher', 'label' => 'Leher'],
                      ['condition' => $hasAccessKondisiFisikThorax, 'url' => 'pemeriksaan_fisik/kondisi_fisik/thorax', 'label' => 'Thorax'],
                      ['condition' => $hasAccessKondisiFisikAbdomenUrogenital, 'url' => 'pemeriksaan_fisik/kondisi_fisik/abdomen_urogenital', 'label' => 'Abdomen & Urogenital'],
                      ['condition' => $hasAccessKondisiFisikAnorectalGenital, 'url' => 'pemeriksaan_fisik/kondisi_fisik/anorectal_genital', 'label' => 'Anorectal & Genital'],
                      ['condition' => $hasAccessKondisiFisikEkstremitas, 'url' => 'pemeriksaan_fisik/kondisi_fisik/ekstremitas', 'label' => 'Ekstremitas'],
                      ['condition' => $hasAccessKondisiFisikNeurologis, 'url' => 'pemeriksaan_fisik/kondisi_fisik/neurologis', 'label' => 'Neurologis']
                  ] as $menuItem)
                      @if ($menuItem['condition'])
                          <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                      @endif
                  @endforeach
                </ul>
              </li>
            </ul>
          </li>
          @endif
          @if ($hasAccessTandaVital || $hasAccessSpirometri || $hasAccessAudiometri || $hasAccessEkg || $hasAccessThreadmill || $hasAccessRontgenThorax || $hasAccessRontgenLumbosacral || $hasAccessUSGUbdomain || $hasAccessFarminghamScore)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-hospital-user" style="padding-right: 5px;font-size: 18px;color: #fff;"></i>
              <span>MCU Data</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessTandaVital, 'url' => 'pemeriksaan_fisik/tanda_vital', 'label' => 'Tanda Vital'],
                ['condition' => $hasAccessSpirometri, 'url' => 'poli/spirometri', 'label' => 'Spirometri'],
                ['condition' => $hasAccessAudiometri, 'url' => 'poli/audiometri', 'label' => 'Audiometri'],
                ['condition' => $hasAccessEkg, 'url' => 'poli/ekg', 'label' => 'EKG'],
                ['condition' => $hasAccessThreadmill, 'url' => 'poli/threadmill', 'label' => 'Treadmill'],
                ['condition' => $hasAccessRontgenThorax, 'url' => 'poli/rontgen_thorax', 'label' => 'Rontgen Thorax'],
                ['condition' => $hasAccessRontgenLumbosacral, 'url' => 'poli/rontgen_lumbosacral', 'label' => 'Rontgen Lumbosacral'],
                ['condition' => $hasAccessUSGUbdomain, 'url' => 'poli/usg_ubdomain', 'label' => 'USG Abdomain'],
                ['condition' => $hasAccessFarminghamScore, 'url' => 'poli/farmingham_score', 'label' => 'Farmingham Score'],
                ['condition' => $hasAccessTandaVital, 'url' => 'laboratorium/daftar_tindakan', 'label' => 'Laboratorium']
              ] as $menuItem)
                <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessTarifLaboratorium || $hasAccessKategoriLaboratorium || $hasAccessSatuanLaboratorium || $hasAccessRentangKenormalanLaboratorium || $hasAccessRentangTemplating)
          <li class="sidebar-main-title">
            <div>
              <h6>LAB & OBAT</h6>
            </div>
          </li>
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-bars" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Parameter</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessTarifLaboratorium, 'url' => 'laboratorium/tarif', 'label' => 'Daftar Tindakan'],
                ['condition' => $hasAccessKategoriLaboratorium, 'url' => 'laboratorium/kategori', 'label' => 'Kategori'],
                ['condition' => $hasAccessSatuanLaboratorium, 'url' => 'laboratorium/satuan', 'label' => 'Satuan'],
                ['condition' => $hasAccessRentangKenormalanLaboratorium, 'url' => 'laboratorium/rentang_kenormalan', 'label' => 'Rentang Kenormalan'],
                ['condition' => $hasAccessRentangTemplating, 'url' => 'laboratorium/templating', 'label' => 'Templating']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessTindakanLaboratorium)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.laboratorium.daftar_tindakan') }}">
              <i class="fa fa-flask" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Tindakan</span>
            </a>
          </li>
          @endif
          @if (
            $hasAccessValidasiKesimpulan || $hasAccessValidasiMcu || $hasAccessArciveMCU || $hasAccessArciveLaboratorium ||
            $hasAccessLaporanPenjualan || $hasAccessLaporanKuitansi || $hasAccessLaporanInsentif ||
            $hasAccessRekapPemeriksaanFisik || $hasAccessRekapVital || $hasAccessRekapSpirometri ||
            $hasAccessRekapAudiometri || $hasAccessRekapEKG || $hasAccessRekapThreadmill ||
            $hasAccessRekapRontgenThorax || $hasAccessRekapRontgenLumbosacral ||
            $hasAccessRekapUSGUbdomain || $hasAccessRekapFarminghamScore
          )
          <li class="sidebar-main-title">
            <div>
              <h6>LAPORAN AMC</h6>
            </div>
          </li>
          @endif
          @if ($hasAccessValidasiKesimpulan)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.laporan.validasi_rekap_kesimpulan') }}">
              <i class="fa fa-book" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Hasil Kesimpulan</span>
            </a>
          </li>
          @endif
          @if ($hasAccessValidasiMcu)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="{{ route('admin.laporan.validasi_mcu') }}">
              <i class="fa fa-file-pdf-o" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Validasi MCU</span>
            </a>
          </li>
          @endif
          @if ($hasAccessArciveMCU || $hasAccessArciveLaboratorium)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-folder-tree" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Berkas Tindakan</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessArciveMCU, 'url' => 'laporan/berkas/mcu', 'label' => 'MCU'],
                ['condition' => $hasAccessArciveMCUThreadmill, 'url' => 'laporan/berkas/mcu_threadmill', 'label' => 'Treadmill'],
                ['condition' => $hasAccessArciveLaboratorium, 'url' => 'laporan/berkas/laboratorium', 'label' => 'Laboratorium']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessLaporanPenjualan || $hasAccessLaporanKuitansi || $hasAccessLaporanInsentif)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-cash-register" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Transaksi</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessLaporanPenjualan, 'url' => 'laporan/transaksi/penjualan', 'label' => 'Tindakan'],
                ['condition' => $hasAccessLaporanInsentif, 'url' => 'laporan/transaksi/insentif', 'label' => 'Insentif'],
                ['condition' => $hasAccessLaporanKuitansi, 'url' => 'laporan/transaksi/kuitansi', 'label' => 'Kuintansi'],
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if (
            $hasAccessRekapPemeriksaanFisik || $hasAccessRekapVital || $hasAccessRekapSpirometri ||
            $hasAccessRekapAudiometri || $hasAccessRekapEKG || $hasAccessRekapThreadmill ||
            $hasAccessRekapRontgenThorax || $hasAccessRekapRontgenLumbosacral ||
            $hasAccessRekapUSGUbdomain || $hasAccessRekapFarminghamScore
          )
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-book" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Rekapitulasi</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessRekapPemeriksaanFisik, 'url' => 'laporan/rekap/pemeriksaan_fisik', 'label' => 'Pemeriksaan Fisik'],
                ['condition' => $hasAccessRekapVital, 'url' => 'laporan/rekap/vital', 'label' => 'Vital'],
                ['condition' => $hasAccessRekapSpirometri, 'url' => 'laporan/rekap/spirometri', 'label' => 'Spirometri'],
                ['condition' => $hasAccessRekapAudiometri, 'url' => 'laporan/rekap/audiometri', 'label' => 'Audiometri'],
                ['condition' => $hasAccessRekapEKG, 'url' => 'laporan/rekap/ekg', 'label' => 'EKG'],
                ['condition' => $hasAccessRekapThreadmill, 'url' => 'laporan/rekap/threadmill', 'label' => 'Treadmill'],
                ['condition' => $hasAccessRekapRontgenThorax, 'url' => 'laporan/rekap/rontgen_thorax', 'label' => 'Rontgen Thorax'],
                ['condition' => $hasAccessRekapRontgenLumbosacral, 'url' => 'laporan/rekap/rontgen_lumbosacral', 'label' => 'Rontgen Lumbosacral'],
                ['condition' => $hasAccessRekapUSGUbdomain, 'url' => 'laporan/rekap/usg_ubdomain', 'label' => 'USG Abdomain'],
                ['condition' => $hasAccessRekapFarminghamScore, 'url' => 'laporan/rekap/farmingham_score', 'label' => 'Framingham Score'],
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          <!-- Pengaturan Section -->
          @if ($hasAccessMasterData || $hasAccessPetugas)
          <li class="sidebar-main-title">
            <div>
              <h6>PENGATURAN</h6>
            </div>
          </li>
          @endif
          @if ($hasAccessMasterData)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-book" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Master Data</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessMasterPerusahaan, 'url' => 'masterdata/daftar_perusahaan', 'label' => 'Perusahaan'],
                ['condition' => $hasAccessPaketHarga, 'url' => 'masterdata/daftar_paket_mcu', 'label' => 'Paket Harga'],
                ['condition' => $hasAccessJasaPelayanan, 'url' => 'masterdata/daftar_jasa_pelayanan', 'label' => 'Jasa Pelayanan'],
                ['condition' => $hasAccessDepartemenPeserta, 'url' => 'masterdata/daftar_departemen_peserta', 'label' => 'Departemen Peserta'],
                ['condition' => $hasAccessMemberMcu, 'url' => 'masterdata/daftar_member_mcu', 'label' => 'Member AMC'],
                ['condition' => $hasAccessPartnerAMC, 'url' => 'masterdata/daftar_partner_amc', 'label' => 'Partner AMC'],
                ['condition' => $hasAccessDaftarBank, 'url' => 'masterdata/daftar_bank', 'label' => 'Daftar Bank'],
                ['condition' => $hasAccessMasterKesimpulan, 'url' => 'masterdata/daftar_kesimpulan', 'label' => 'Kesimpulan'],
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessPetugas)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-user-md" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Petugas</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessPenggunaAplikasi || $hasAccessPermission, 'url' => 'admin/pengguna_aplikasi', 'label' => 'Pengguna Aplikasi'],
                ['condition' => $hasAccessHakAkses || $hasAccessPermission, 'url' => 'admin/role', 'label' => 'Hak Akses'],
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessErrorLog || $hasAccessUpdateLog)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa fa-dev" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Developer Area</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessErrorLog, 'url' => 'dev/error_log_app', 'label' => 'Error Log'],
                ['condition' => $hasAccessUpdateLog, 'url' => 'dev/update_logs', 'label' => 'Log Update'],
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessArciveMCUPerusahaan || $hasAccessArciveMCUThreadmillPerusahaan || $hasAccessArciveLaboratoriumPerusahaan)
          <li class="sidebar-main-title">
            <div>
              <h6>BERKAS PERUSAHAAN</h6>
            </div>
          </li>
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-folder-tree" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Berkas Tindakan</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessArciveMCUPerusahaan, 'url' => 'laporan/berkas_perusahaan/mcu', 'label' => 'MCU'],
                ['condition' => $hasAccessArciveMCUThreadmillPerusahaan, 'url' => 'laporan/berkas_perusahaan/threadmill', 'label' => 'Treadmill'],
                ['condition' => $hasAccessArciveLaboratoriumPerusahaan, 'url' => 'laporan/berkas_perusahaan/laboratorium', 'label' => 'Laboratorium'],
                ['condition' => $hasAccessLaporanKuitansiPerusahaan, 'url' => 'laporan/berkas_perusahaan/kuitansi', 'label' => 'Kuitansi']
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
          @if ($hasAccessRekapPemeriksaanFisikPerusahaan || $hasAccessRekapVitalPerusahaan || $hasAccessRekapSpirometriPerusahaan || $hasAccessRekapAudiometriPerusahaan || $hasAccessRekapEKGPerusahaan || $hasAccessRekapThreadmillPerusahaan || $hasAccessRekapRontgenThoraxPerusahaan || $hasAccessRekapRontgenLumbosacralPerusahaan || $hasAccessRekapUSGUbdomainPerusahaan || $hasAccessRekapFarminghamScorePerusahaan)
          <li class="sidebar-list">
            <i class="fa fa-thumb-tack"></i>
            <a class="sidebar-link sidebar-title" href="javascript:void(0)">
              <i class="fa-solid fa-book" style="padding-right: 10px;font-size: 20px;color: #fff;"></i>
              <span>Rekapitulasi</span>
            </a>
            <ul class="sidebar-submenu">
              @foreach([
                ['condition' => $hasAccessRekapPemeriksaanFisikPerusahaan, 'url' => 'laporan/rekap_perusahaan/pemeriksaan_fisik', 'label' => 'Pemeriksaan Fisik'],
                ['condition' => $hasAccessRekapVitalPerusahaan, 'url' => 'laporan/rekap_perusahaan/vital', 'label' => 'Vital'],
                ['condition' => $hasAccessRekapSpirometriPerusahaan, 'url' => 'laporan/rekap_perusahaan/spirometri', 'label' => 'Spirometri'],
                ['condition' => $hasAccessRekapAudiometriPerusahaan, 'url' => 'laporan/rekap_perusahaan/audiometri', 'label' => 'Audiometri'],
                ['condition' => $hasAccessRekapEKGPerusahaan, 'url' => 'laporan/rekap_perusahaan/ekg', 'label' => 'EKG'],
                ['condition' => $hasAccessRekapThreadmillPerusahaan, 'url' => 'laporan/rekap_perusahaan/threadmill', 'label' => 'Treadmill'],
                ['condition' => $hasAccessRekapRontgenThoraxPerusahaan, 'url' => 'laporan/rekap_perusahaan/rontgen_thorax', 'label' => 'Rontgen Thorax'],
                ['condition' => $hasAccessRekapRontgenLumbosacralPerusahaan, 'url' => 'laporan/rekap_perusahaan/rontgen_lumbosacral', 'label' => 'Rontgen Lumbosacral'],
                ['condition' => $hasAccessRekapUSGUbdomainPerusahaan, 'url' => 'laporan/rekap_perusahaan/usg_ubdomain', 'label' => 'USG Abdomain'],
                ['condition' => $hasAccessRekapFarminghamScorePerusahaan, 'url' => 'laporan/rekap_perusahaan/farmingham_score', 'label' => 'Framingham Score'],
              ] as $menuItem)
                @if ($menuItem['condition'])
                  <li><a href="{{ url($menuItem['url']) }}">{{ $menuItem['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </li>
          @endif
        </ul>
      </div>
      <div class="right-arrow" id="right-arrow">
        <i data-feather="arrow-right"></i>
      </div>
    </nav>      
  </div>
</div>