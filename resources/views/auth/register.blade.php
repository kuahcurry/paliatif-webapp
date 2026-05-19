<x-guest-layout>
    <div class="auth-form-header">
        <h2>Buat Akun Baru</h2>
        <p>Mulai perjalanan spiritual Anda bersama Ruang Hening</p>
    </div>

    <div class="auth-card">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <label for="name">Nama/alias</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama atau alias Anda">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Religion -->
            <div class="mt-4">
                <label for="religion">Agama</label>
                <select id="religion" name="religion">
                    <option value="">{{ __('Pilih agama') }}</option>
                    <option value="Islam" @selected(old('religion') === 'Islam')>Islam</option>
                    <option value="Kristen Protestan" @selected(old('religion') === 'Kristen Protestan')>Kristen Protestan</option>
                    <option value="Kristen Katolik" @selected(old('religion') === 'Kristen Katolik')>Kristen Katolik</option>
                    <option value="Hindu" @selected(old('religion') === 'Hindu')>Hindu</option>
                    <option value="Buddha" @selected(old('religion') === 'Buddha')>Buddha</option>
                    <option value="Konghucu" @selected(old('religion') === 'Konghucu')>Konghucu</option>
                </select>
                <x-input-error :messages="$errors->get('religion')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="contoh@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password">Kata sandi</label>
                <div class="password-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)" aria-label="Tampilkan kata sandi">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="password_confirmation">Konfirmasi kata sandi</label>
                <div class="password-wrapper">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan konfirmasi kata sandi">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-6" style="text-align: center;">
                <button type="submit" style="width: 100%;">
                    {{ __('Daftar') }}
                </button>
            </div>
        </form>
    </div>

    <div class="auth-bottom">
        {{ __('Sudah punya akun?') }}
        <a href="{{ route('login') }}">{{ __('Masuk') }}</a>
    </div>
</x-guest-layout>
