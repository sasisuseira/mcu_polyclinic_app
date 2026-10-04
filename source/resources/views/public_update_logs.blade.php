<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Informasi pembaruan aplikasi MCU dan Poliklinik.">
    <title>Info Pembaruan | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('mofi/assets/images/logo/logo_amc.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { color-scheme: light; --ink: #182b32; --muted: #62747a; --line: #dce5e4; --green: #087e70; --coral: #d6654f; --paper: #f4f7f5; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--paper); color: var(--ink); font-family: 'DM Sans', sans-serif; }
        .topbar { background: #fff; border-bottom: 1px solid var(--line); }
        .topbar-inner { width: min(1040px, calc(100% - 40px)); min-height: 78px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 13px; color: var(--ink); text-decoration: none; font-family: 'Manrope', sans-serif; font-weight: 800; }
        .brand img { width: 43px; height: 43px; object-fit: contain; }
        .brand span { display: grid; gap: 1px; }
        .brand small { color: var(--muted); font: 600 11px 'DM Sans', sans-serif; }
        .topbar a.back-link { color: var(--green); font-size: 14px; font-weight: 700; text-decoration: none; }
        .intro { background: #e4f0ec; border-bottom: 1px solid #d3e4df; }
        .intro-inner { width: min(1040px, calc(100% - 40px)); margin: 0 auto; padding: 48px 0 42px; }
        .eyebrow { margin: 0 0 10px; color: var(--coral); font-size: 12px; font-weight: 700; text-transform: uppercase; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        h1 { max-width: 700px; margin: 0; font-size: clamp(32px, 5vw, 48px); line-height: 1.12; }
        .intro p:last-child { margin: 12px 0 0; color: #496168; font-size: 16px; }
        main { width: min(1040px, calc(100% - 40px)); margin: 0 auto; padding: 42px 0 72px; }
        .feed-heading { display: flex; justify-content: space-between; align-items: baseline; gap: 12px; margin-bottom: 22px; }
        .feed-heading h2 { margin: 0; font-size: 20px; }
        .feed-heading span { color: var(--muted); font-size: 13px; }
        .timeline { border-left: 1px solid #bdcfca; margin-left: 115px; }
        .entry { position: relative; padding: 0 0 34px 30px; }
        .entry:last-child { padding-bottom: 0; }
        .entry::before { position: absolute; top: 5px; left: -6px; width: 11px; height: 11px; border: 2px solid var(--paper); border-radius: 50%; background: var(--green); box-shadow: 0 0 0 1px var(--green); content: ''; }
        .date { position: absolute; top: 0; right: calc(100% + 30px); width: 86px; color: var(--muted); text-align: right; font-size: 13px; }
        .date strong { display: block; color: var(--ink); font: 700 16px 'Manrope', sans-serif; }
        .entry-header { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 9px; }
        .entry h3 { margin: 0; font-size: 21px; }
        .tag { padding: 4px 9px; border-radius: 3px; background: #d9ebe5; color: #12695f; font-size: 11px; font-weight: 700; }
        .tag.version { background: #f7e5df; color: #a34837; }
        .summary { margin: 0 0 12px; color: #40575e; font-size: 15px; line-height: 1.7; }
        .markdown { color: #40575e; font-size: 14px; line-height: 1.75; overflow-wrap: anywhere; }
        .markdown > :first-child { margin-top: 0; }
        .markdown > :last-child { margin-bottom: 0; }
        .markdown h1, .markdown h2, .markdown h3, .markdown h4 { margin: 18px 0 8px; color: var(--ink); font-size: 16px; }
        .markdown ul, .markdown ol { padding-left: 22px; }
        .markdown blockquote { margin: 14px 0; padding: 2px 0 2px 15px; border-left: 3px solid #9bbcb2; color: var(--muted); }
        .markdown pre { overflow-x: auto; padding: 14px; background: #e8eeeb; border-radius: 4px; }
        .markdown code { padding: 2px 4px; background: #e8eeeb; border-radius: 3px; font-size: .92em; }
        .markdown pre code { padding: 0; background: transparent; }
        .markdown a { color: var(--green); text-decoration: underline; text-underline-offset: 2px; }
        .empty { padding: 58px 20px; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); text-align: center; }
        .empty h2 { margin: 0 0 8px; font-size: 20px; }
        .empty p { margin: 0; color: var(--muted); }
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 34px; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid var(--line); border-radius: 3px; background: #fff; color: var(--ink); text-decoration: none; font-size: 13px; }
        .pagination span[aria-current="page"] { border-color: var(--green); background: var(--green); color: #fff; }
        .pagination span[aria-disabled="true"] { color: #9aa6a8; }
        footer { padding: 22px 20px; border-top: 1px solid var(--line); color: var(--muted); text-align: center; font-size: 12px; }
        @media (max-width: 600px) {
            .topbar-inner, .intro-inner, main { width: min(100% - 32px, 1040px); }
            .topbar-inner { min-height: 68px; }
            .brand img { width: 36px; height: 36px; }
            .brand { font-size: 13px; }
            .brand small { font-size: 10px; }
            .topbar a.back-link { font-size: 12px; }
            .intro-inner { padding: 34px 0 30px; }
            .intro p:last-child { font-size: 14px; }
            main { padding: 30px 0 50px; }
            .timeline { margin-left: 0; border-left: 0; }
            .entry { padding: 0 0 30px; }
            .entry::before { display: none; }
            .date { position: static; display: flex; width: auto; gap: 6px; margin-bottom: 7px; text-align: left; }
            .date strong { font-size: 13px; }
            .entry h3 { flex-basis: 100%; font-size: 19px; }
            .summary { font-size: 14px; }
            .feed-heading { align-items: start; flex-direction: column; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ url('/infopembaruan') }}">
                <img src="{{ asset('mofi/assets/images/logo/logo_amc.png') }}" alt="Logo AMC">
                <span>{{ config('app.name') }}<small>Informasi pembaruan aplikasi</small></span>
            </a>
            <a class="back-link" href="{{ url('/') }}">Beranda</a>
        </div>
    </header>
    <section class="intro">
        <div class="intro-inner">
            <p class="eyebrow">Pembaruan aplikasi</p>
            <h1>Perubahan terbaru, tercatat dengan jelas.</h1>
            <p>Catatan rilis untuk fitur, peningkatan, dan perbaikan aplikasi.</p>
        </div>
    </section>
    <main>
        <div class="feed-heading">
            <h2>Riwayat pembaruan</h2>
            <span>{{ $updateLogs->total() }} pembaruan publik</span>
        </div>
        @if ($updateLogs->isNotEmpty())
            <div class="timeline">
                @foreach ($updateLogs as $updateLog)
                <article class="entry">
                    <time class="date" datetime="{{ $updateLog->released_at->format('Y-m-d') }}">
                        <strong>{{ $updateLog->released_at->translatedFormat('d M Y') }}</strong>
                    </time>
                    <div class="entry-header">
                        <h3>{{ $updateLog->title }}</h3>
                        <span class="tag">{{ $updateLog->category }}</span>
                        @if ($updateLog->version)
                            <span class="tag version">v{{ $updateLog->version }}</span>
                        @endif
                    </div>
                    <div class="markdown summary">{!! \Illuminate\Support\Str::markdown($updateLog->summary, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
                    <div class="markdown">{!! \Illuminate\Support\Str::markdown($updateLog->details, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
                </article>
                @endforeach
            </div>
        @else
            <section class="empty">
                <h2>Belum ada pembaruan publik</h2>
                <p>Informasi rilis publik akan ditampilkan di halaman ini.</p>
            </section>
        @endif

        @if ($updateLogs->hasPages())
            <nav class="pagination" aria-label="Navigasi halaman">
                @if ($updateLogs->onFirstPage())
                    <span aria-disabled="true">Sebelumnya</span>
                @else
                    <a href="{{ $updateLogs->previousPageUrl() }}">Sebelumnya</a>
                @endif
                @foreach ($updateLogs->getUrlRange(max(1, $updateLogs->currentPage() - 2), min($updateLogs->lastPage(), $updateLogs->currentPage() + 2)) as $page => $url)
                    @if ($page === $updateLogs->currentPage())
                        <span aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                @if ($updateLogs->hasMorePages())
                    <a href="{{ $updateLogs->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span aria-disabled="true">Berikutnya</span>
                @endif
            </nav>
        @endif
    </main>
    <footer>&copy; {{ now()->year }} {{ config('app.name') }}</footer>
</body>
</html>