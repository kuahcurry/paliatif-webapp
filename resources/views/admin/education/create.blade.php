@push('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />
@endpush

<x-app-layout :hideNavigation="true" :hideHeader="true" bodyClass="antialiased" pageClass="min-h-screen admin-page">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <x-app-sidebar />
        </aside>

        <main class="admin-main">
            <x-app-topbar
                class="admin-topbar"
                title="Tambah Modul Edukasi"
                subtitle="Buat konten baru"
                :showMenuButton="true"
            />

            <div class="card" style="max-width: 720px;">
                <div class="section-header">
                    <div>
                        <h4>Form Modul Baru</h4>
                        <p>Isi detail modul edukasi yang akan ditampilkan kepada pengguna</p>
                    </div>
                    <a href="{{ route('admin.education.index') }}" class="ghost-button">← Kembali</a>
                </div>

                <form method="POST" action="{{ route('admin.education.store') }}" class="form-body">
                    @csrf

                    <div class="field">
                        <label for="title">Judul Modul</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required>
                        <x-input-error :messages="$errors->get('title')" />
                    </div>

                    <div class="field">
                        <label for="summary">Ringkasan</label>
                        <textarea id="summary" name="summary" rows="2" required>{{ old('summary') }}</textarea>
                        <x-input-error :messages="$errors->get('summary')" />
                    </div>

                    <div class="field">
                        <label for="type">Tipe Modul</label>
                        <select id="type" name="type" required onchange="toggleTypeFields()">
                            <option value="article" @selected(old('type') === 'article')>Artikel</option>
                            <option value="video" @selected(old('type') === 'video')>Video</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" />
                    </div>

                    <div class="field" id="content-field">
                        <label for="content">Konten Artikel</label>
                        <textarea id="content" name="content" rows="8">{{ old('content') }}</textarea>
                        <x-input-error :messages="$errors->get('content')" />
                    </div>

                    <div class="field" id="url-field" style="display: none;">
                        <label for="url">URL Video (YouTube)</label>
                        <input id="url" type="url" name="url" value="{{ old('url') }}" placeholder="https://www.youtube.com/watch?v=...">
                        <x-input-error :messages="$errors->get('url')" />
                    </div>

                    <div class="field" id="video-file-field" style="display: none;">
                        <label for="video_file">Upload Video (maks. 100MB, mp4/mov/avi/mkv)</label>
                        <input id="video_file" type="file" name="video_file" accept=".mp4,.mov,.avi,.mkv">
                        <x-input-error :messages="$errors->get('video_file')" />
                    </div>

                    <div class="field" id="image-file-field">
                        <label for="image_file">Upload Gambar Artikel (maks. 5MB, jpg/jpeg/webp)</label>
                        <input id="image_file" type="file" name="image_file" accept=".jpg,.jpeg,.webp">
                        <x-input-error :messages="$errors->get('image_file')" />
                    </div>

                    <div class="field">
                        <label>Tag</label>
                        <div class="checkbox-grid">
                            @php $tagOptions = ['coping' => 'Coping dan tenang', 'communication' => 'Komunikasi', 'grief' => 'Manajemen duka', 'spiritual_support' => 'Dukungan spiritual']; @endphp
                            @foreach ($tagOptions as $value => $label)
                                <label><input type="checkbox" name="tags[]" value="{{ $value }}" @checked(in_array($value, old('tags', [])))> {{ $label }}</label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('tags')" />
                    </div>

                    <div class="field">
                        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Aktif</label>
                    </div>

                    <div class="field">
                        <label><input type="checkbox" name="is_highlighted" value="1" @checked(old('is_highlighted', false))> Tambahkan ke aktivitas yang disarankan</label>
                        <small style="font-size: 0.72rem; color: #94a3b8;">Modul ini akan ditampilkan di dashboard pengguna sebagai rekomendasi.</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="primary-button">Simpan</button>
                        <a href="{{ route('admin.education.index') }}" class="ghost-button">Batal</a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function toggleTypeFields() {
            const type = document.getElementById('type').value;
            document.getElementById('content-field').style.display = type === 'article' ? '' : 'none';
            document.getElementById('image-file-field').style.display = type === 'article' ? '' : 'none';
            document.getElementById('url-field').style.display = type === 'video' ? '' : 'none';
            document.getElementById('video-file-field').style.display = type === 'video' ? '' : 'none';
        }
        toggleTypeFields();
    </script>

    <style>
        :root { --surface: #fff; --muted: #64748b; --shadow: 0 12px 28px rgba(15,23,42,0.08); }
        .admin-page { background: #f4f6fb; font-family: 'Manrope', sans-serif; }
        .admin-layout { display: grid; grid-template-columns: 260px 1fr; min-height: 100vh; }
        .admin-sidebar { background: var(--surface); padding: 28px 20px; border-right: 1px solid #edf2f7; display: flex; flex-direction: column; gap: 24px; }
        .sidebar-brand { display: flex; gap: 12px; align-items: center; font-weight: 700; }
        .brand-icon { width: 42px; height: 42px; border-radius: 16px; background: #dff3df; display: grid; place-items: center; }
        .brand-icon span { width: 22px; height: 22px; border-radius: 999px; background: #63b96b; display: block; }
        .sidebar-brand h1 { font-size: 0.95rem; color: #0f172a; }
        .sidebar-brand p { font-size: 0.8rem; color: var(--muted); }
        .sidebar-nav { display: flex; flex-direction: column; gap: 10px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 12px; color: var(--muted); text-decoration: none; font-size: 0.9rem; }
        .nav-icon { width: 20px; height: 20px; display: grid; place-items: center; }
        .nav-icon svg { width: 18px; height: 18px; }
        .nav-item.is-active, .nav-item:hover { background: #e1f1e1; color: #256c32; }
        .sidebar-footer { margin-top: auto; }
        .footer-card { background: #f8fafc; border-radius: 16px; padding: 16px; font-size: 0.75rem; color: var(--muted); }
        .footer-title { font-weight: 700; color: #0f172a; }
        .admin-main { padding: 26px 32px 48px; display: flex; flex-direction: column; gap: 8px; }
        .admin-topbar { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
        .admin-topbar > div:not(.topbar-actions) { grid-column: 2; text-align: center; }
        .admin-topbar .ghost-button { grid-column: 1; justify-self: start; }
        .admin-topbar .topbar-actions { grid-column: 3; justify-self: end; }
        .admin-topbar h2 { font-size: 1.4rem; font-weight: 700; }
        .admin-topbar p { color: var(--muted); font-size: 0.85rem; }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }
        .icon-button { position: relative; border: none; background: #fff; box-shadow: var(--shadow); border-radius: 12px; width: 38px; height: 38px; cursor: pointer; display: grid; place-items: center; color: #475569; text-decoration: none; font-size: 1rem; font-weight: 600; }
        .user-chip { display: flex; align-items: center; gap: 10px; background: #fff; border-radius: 16px; padding: 6px 12px; box-shadow: var(--shadow); border: none; cursor: pointer; font-family: inherit; }
        .avatar { width: 34px; height: 34px; border-radius: 999px; background: #e0f2fe; color: #1d4ed8; font-weight: 700; display: grid; place-items: center; font-size: 0.85rem; }
        .user-name { font-size: 0.85rem; font-weight: 600; color: #0f172a; }
        .user-role { font-size: 0.75rem; color: var(--muted); }
        .card { background: var(--surface); border-radius: 20px; padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 16px; }
        .section-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .section-header h4 { font-weight: 700; }
        .section-header p { font-size: 0.78rem; color: var(--muted); }
        .ghost-button { border: 1px solid #e2e8f0; background: #fff; color: #475569; border-radius: 12px; padding: 6px 12px; font-size: 0.78rem; cursor: pointer; text-decoration: none; display: inline-flex; }
        .primary-button { background: #4f9b4f; color: #fff; border: none; border-radius: 12px; padding: 8px 16px; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
        .form-body { display: grid; gap: 16px; }
        .field { display: grid; gap: 6px; }
        .field label { font-size: 0.82rem; color: var(--muted); }
        .field input, .field select, .field textarea { border: 1px solid #e2e8f0; border-radius: 12px; padding: 8px 12px; font-size: 0.85rem; width: 100%; }
        .checkbox-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .checkbox-grid label { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: #0f172a; }
        .form-actions { display: flex; align-items: center; gap: 12px; padding-top: 8px; }
        @media (max-width: 900px) {
            .admin-layout { grid-template-columns: 1fr; }
            .admin-sidebar { position: sticky; top: 0; z-index: 10; flex-direction: row; overflow-x: auto; }
            .sidebar-brand, .sidebar-footer { display: none; }
            .sidebar-nav { flex-direction: row; }
            .nav-item { white-space: nowrap; }
            .admin-topbar { display: flex; flex-direction: column; align-items: flex-start; gap: 12px; }
            .admin-topbar > div:not(.topbar-actions) { text-align: left; }
            .admin-topbar .topbar-actions { justify-self: auto; align-self: flex-start; }
            .checkbox-grid { grid-template-columns: 1fr; }
        }
    </style>
</x-app-layout>
