@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
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
            />

            <div class="card" style="max-width: 720px;">
                <div class="section-header">
                    <div>
                        <h4>Form Modul Baru</h4>
                        <p>Isi detail modul edukasi yang akan ditampilkan kepada pengguna</p>
                    </div>
                    <a href="{{ route('admin.education.index') }}" class="ghost-button">← Kembali</a>
                </div>

                <form method="POST" action="{{ route('admin.education.store') }}" class="form-grid" enctype="multipart/form-data">
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
                                <label class="checkbox-label"><input type="checkbox" name="tags[]" value="{{ $value }}" @checked(in_array($value, old('tags', [])))> {{ $label }}</label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('tags')" />
                    </div>

                    <div class="field">
                        <label class="checkbox-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Aktif</label>
                    </div>

                    <div class="field">
                        <label class="checkbox-label"><input type="checkbox" name="is_highlighted" value="1" @checked(old('is_highlighted', false))> Tambahkan ke aktivitas yang disarankan</label>
                        <small class="text-muted">Modul ini akan ditampilkan di dashboard pengguna sebagai rekomendasi.</small>
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

    @include('admin.partials.admin-styles')

    <style>
        .checkbox-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: var(--text); cursor: pointer; }
        .checkbox-label input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); border-radius: 4px; }
        @media (max-width: 900px) { .checkbox-grid { grid-template-columns: 1fr; } }
    </style>
</x-app-layout>
