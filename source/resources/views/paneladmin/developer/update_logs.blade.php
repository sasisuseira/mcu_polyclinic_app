@extends('paneladmin.templateadmin')

@section('konten_utama_admin')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h4 class="mb-1">Log Update</h4>
                    <span class="text-muted">Riwayat perubahan dan rilis aplikasi.</span>
                </div>
                <button type="button" class="btn btn-primary" id="btn-create-update-log">
                    <i class="fa fa-plus me-1"></i> Tambah Update
                </button>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <div class="fw-semibold mb-1">Periksa kembali data yang diisi.</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($updateLogs->isEmpty())
                    <div class="update-log-empty text-center py-5">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        <h5 class="mt-3">Belum ada catatan update</h5>
                        <p class="text-muted mb-0">Catat perubahan aplikasi pertama untuk memulai timeline.</p>
                    </div>
                @else
                    <div class="update-log-timeline">
                        @foreach ($updateLogs as $updateLog)
                            <article class="update-log-entry">
                                <div class="update-log-date">
                                    <span>{{ $updateLog->released_at->format('d') }}</span>
                                    <small>{{ $updateLog->released_at->translatedFormat('M Y') }}</small>
                                </div>
                                <div class="update-log-content">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary">{{ $updateLog->category }}</span>
                                        @if ($updateLog->version)
                                            <span class="badge bg-light text-dark">v{{ $updateLog->version }}</span>
                                        @endif
                                        <span class="badge {{ $updateLog->visibility === 'public' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $updateLog->visibility === 'public' ? 'Publik' : 'Internal' }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-2">{{ $updateLog->title }}</h5>
                                            <div class="markdown-content update-log-summary mb-2">{!! \Illuminate\Support\Str::markdown($updateLog->summary, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
                                            <details class="update-log-details">
                                                <summary>Lihat detail perubahan</summary>
                                                <div class="markdown-content pt-2">{!! \Illuminate\Support\Str::markdown($updateLog->details, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}</div>
                                            </details>
                                            <small class="text-muted d-block mt-3">Dicatat oleh {{ $updateLog->author_name }}</small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-light btn-sm js-edit-update-log"
                                                aria-label="Edit {{ $updateLog->title }}"
                                                data-id="{{ $updateLog->id }}"
                                                data-title="{{ $updateLog->title }}"
                                                data-version="{{ $updateLog->version }}"
                                                data-category="{{ $updateLog->category }}"
                                                data-summary="{{ $updateLog->summary }}"
                                                data-details="{{ $updateLog->details }}"
                                                data-released-at="{{ $updateLog->released_at->format('Y-m-d') }}"
                                                data-visibility="{{ $updateLog->visibility }}">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </button>
                                            <form method="POST" action="{{ route('dev.update-logs.destroy', $updateLog) }}" onsubmit="return confirm('Hapus catatan update ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm text-danger" aria-label="Hapus {{ $updateLog->title }}">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="update-log-modal" tabindex="-1" aria-labelledby="update-log-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" id="update-log-form" action="{{ route('dev.update-logs.store') }}">
                @csrf
                <input type="hidden" name="_method" id="update-log-method" value="POST">
                <input type="hidden" name="update_log_id" id="update-log-id" value="{{ old('update_log_id') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="update-log-modal-title">Tambah Log Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="update-log-title" class="form-label">Judul update</label>
                            <input id="update-log-title" name="title" type="text" maxlength="160" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="update-log-version" class="form-label">Versi</label>
                            <input id="update-log-version" name="version" type="text" maxlength="40" class="form-control" value="{{ old('version') }}" placeholder="Contoh: 2.4.0">
                        </div>
                        <div class="col-md-4">
                            <label for="update-log-category" class="form-label">Kategori</label>
                            <select id="update-log-category" name="category" class="form-select" required>
                                <option value="Feature" @selected(old('category') === 'Feature')>Fitur baru</option>
                                <option value="Improvement" @selected(old('category') === 'Improvement')>Peningkatan</option>
                                <option value="Fix" @selected(old('category') === 'Fix')>Perbaikan</option>
                                <option value="Security" @selected(old('category') === 'Security')>Keamanan</option>
                                <option value="Maintenance" @selected(old('category') === 'Maintenance')>Pemeliharaan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="update-log-released-at" class="form-label">Tanggal rilis</label>
                            <input id="update-log-released-at" name="released_at" type="date" class="form-control" value="{{ old('released_at', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="update-log-visibility" class="form-label">Visibilitas</label>
                            <select id="update-log-visibility" name="visibility" class="form-select" required>
                                <option value="internal" @selected(old('visibility', 'internal') === 'internal')>Internal</option>
                                <option value="public" @selected(old('visibility') === 'public')>Publik</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="update-log-summary" class="form-label">Ringkasan (Markdown)</label>
                            <textarea id="update-log-summary" name="summary" rows="2" maxlength="500" class="form-control" required>{{ old('summary') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label for="update-log-details" class="form-label">Detail perubahan (Markdown)</label>
                            <textarea id="update-log-details" name="details" rows="6" maxlength="20000" class="form-control" required>{{ old('details') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css_load')
<style>
    .update-log-timeline { border-left: 2px solid #e7ebef; margin-left: 58px; }
    .update-log-entry { position: relative; padding: 0 0 30px 28px; }
    .update-log-entry:last-child { padding-bottom: 0; }
    .update-log-entry::before { content: ''; position: absolute; left: -7px; top: 4px; width: 12px; height: 12px; border: 3px solid #fff; border-radius: 50%; background: #2f6fed; box-shadow: 0 0 0 1px #2f6fed; }
    .update-log-date { position: absolute; right: calc(100% + 28px); top: -2px; width: 46px; text-align: center; color: #263238; }
    .update-log-date span { display: block; font-size: 22px; font-weight: 700; line-height: 1.1; }
    .update-log-date small { color: #77818b; white-space: nowrap; }
    .update-log-content { max-width: 100%; }
    .update-log-details summary { color: #2f6fed; cursor: pointer; font-size: 13px; }
    .update-log-empty i { color: #9aa4ae; font-size: 30px; }
    .markdown-content > :last-child { margin-bottom: 0; }
    .markdown-content pre { overflow-x: auto; padding: 12px; background: #f4f6f8; border-radius: 4px; }
    .markdown-content code { color: #b42318; }
    @media (max-width: 576px) {
        .update-log-timeline { margin-left: 43px; }
        .update-log-entry { padding-left: 18px; }
        .update-log-date { right: calc(100% + 18px); width: 36px; }
        .update-log-date span { font-size: 18px; }
    }
</style>
@endsection

@section('js_load')
<script>
    (() => {
        const modalElement = document.getElementById('update-log-modal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        const form = document.getElementById('update-log-form');
        const baseAction = @json(route('dev.update-logs.store'));
        const updateAction = @json(url('dev/update_logs'));

        const openCreateForm = () => {
            form.reset();
            form.action = baseAction;
            document.getElementById('update-log-id').value = '';
            document.getElementById('update-log-method').value = 'POST';
            document.getElementById('update-log-released-at').value = new Date().toISOString().slice(0, 10);
            document.getElementById('update-log-modal-title').textContent = 'Tambah Log Update';
            modal.show();
        };

        document.getElementById('btn-create-update-log').addEventListener('click', openCreateForm);
        document.querySelectorAll('.js-edit-update-log').forEach((button) => {
            button.addEventListener('click', () => {
                form.action = `${updateAction}/${button.dataset.id}`;
                document.getElementById('update-log-id').value = button.dataset.id;
                document.getElementById('update-log-method').value = 'PUT';
                document.getElementById('update-log-title').value = button.dataset.title;
                document.getElementById('update-log-version').value = button.dataset.version;
                document.getElementById('update-log-category').value = button.dataset.category;
                document.getElementById('update-log-summary').value = button.dataset.summary;
                document.getElementById('update-log-details').value = button.dataset.details;
                document.getElementById('update-log-released-at').value = button.dataset.releasedAt;
                document.getElementById('update-log-visibility').value = button.dataset.visibility;
                document.getElementById('update-log-modal-title').textContent = 'Edit Log Update';
                modal.show();
            });
        });

        @if ($errors->any())
            const previousId = document.getElementById('update-log-id').value;
            if (previousId) {
                form.action = `${updateAction}/${previousId}`;
                document.getElementById('update-log-method').value = 'PUT';
                document.getElementById('update-log-modal-title').textContent = 'Edit Log Update';
            }
            modal.show();
        @endif
    })();
</script>
@endsection