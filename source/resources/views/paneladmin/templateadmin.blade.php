<!doctype html>
<html lang="en">
<head>
    @include('includes.assetsheader')
    @yield('css_load')
</head>
<body>
    <div class="loader-wrapper"> 
        <div class="loader loader-1">
            <div class="loader-outter"></div>
            <div class="loader-inner"></div>
            <div class="loader-inner-1"></div>
        </div>
    </div>
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper" data-state="default">
        <div class="page-header row" data-state="default">
            @include('includes.header')
        </div>
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
          <!-- Page Sidebar Start-->
          @include('includes.sidebarmenu')
          <!-- Page Sidebar Ends-->
          <div class="page-body">
            <!-- Container-fluid starts-->
            <div class="container-fluid">
              @yield('konten_utama_admin')
            </div>
            <!-- Container-fluid Ends-->
          </div>
          <!-- footer start-->
          <footer class="footer">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12 footer-copyright d-flex flex-wrap align-items-center justify-content-between">
                  <p class="mb-0 f-w-600">Copyright 2024 - <?=date('Y');?> © {{ config('app.name') }}  </p>
                  <p class="mb-0 f-w-600">Session Akan Berakhir Dalam <span id="waktu_relog">0</span> - Hand crafted & made with <svg class="footer-icon">
                      <use href="{{ asset('mofi/assets/svg/icon-sprite.svg#footer-heart')}}"> </use>
                    </svg>
                    by <a href="https://erayadigital.co.id" target="_blank">Eraya Digital Solusindo</a>
                  </p>
                </div>
              </div>
            </div>
          </footer>
        </div>
      </div>
    @include('includes.assetsfooter')
    @yield('js_load')
    <script>
      window.reverbUserId = @json(session('user_id'));
    </script>
    @vite('resources/js/app.js')
<script>
(function () {
    const timeout = document.querySelector('meta[name="session-expire"]').getAttribute('content') * 60 * 1000; // convert menit -> ms
    const timeoutInSeconds = timeout / 1000;
    let timer; // untuk logout
    let countdownInterval; // untuk countdown detik
    let endTime = Date.now() + timeout; // waktu session habis

    // update countdown setiap detik
    function startCountdown() {
        clearInterval(countdownInterval);
        countdownInterval = setInterval(() => {
            const remaining = endTime - Date.now();
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('waktu_relog').innerText = "00:00";
                return;
            }
            const minutes = Math.floor(remaining / 60000);
            const seconds = Math.floor((remaining % 60000) / 1000);
            document.getElementById('waktu_relog').innerText =
                String(minutes).padStart(2,'0') + ":" + String(seconds).padStart(2,'0');
        }, 1000);
    }

    // function logout + alert
    function startTimer() {
        clearTimeout(timer);
        endTime = Date.now() + timeout; // reset waktu habis
        startCountdown();
        timer = setTimeout(() => {
            Swal.fire({
              html: '<div class="mt-3 text-center"><dotlottie-player src="https://lottie.host/53c357e2-68f2-4954-abff-939a52e6a61a/PB4F7KPq65.json" background="transparent" speed="1" style="width:150px;height:150px;margin:0 auto" direction="1" playMode="normal" loop autoplay></dotlottie-player><div><h4>Wow... Anda Sesi Tidak Aktif</h4><p class="text-muted mx-4 mb-0">Anda sudah tidak aktif selama <strong>' + timeoutInSeconds + '</strong> detik ?. Silahkan pilih salah satu aksi berikutnya</p></div></div>',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: 'orange',
              confirmButtonText: 'Logout',
              cancelButtonText: 'Lanjutkan Kerja',
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = '/pintukeluar';
              }else{
                startTimer();
              }
          });
        }, timeout);
    }

    // start pertama kali
    startTimer();

    // reset timer & countdown kalau ada aktivitas user
    ['click','keydown'].forEach(evt => {
        document.addEventListener(evt, startTimer);
    });
})();
</script>
</body>
</html>