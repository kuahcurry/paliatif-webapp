<x-guest-layout>
    <div class="auth-form-header">
        <h2>Selamat Datang</h2>
        <p>Masuk ke akun Ruang Hening Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="auth-card">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password">Kata sandi</label>
                <div class="password-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)" aria-label="Tampilkan kata sandi">
                        <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="mt-4" style="display: flex; align-items: center; justify-content: space-between;">
                <label for="remember_me" class="inline-flex items-center" style="margin-bottom: 0; cursor: pointer;">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span class="ms-2 text-sm" style="color: #64748b; font-weight: 500;">{{ __('Ingat saya') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.82rem;">
                        {{ __('Lupa kata sandi?') }}
                    </a>
                @endif
            </div>

            <div class="mt-6" style="text-align: center;">
                <button type="submit" style="width: 100%;">
                    {{ __('Masuk') }}
                </button>
            </div>
        </form>
    </div>

    <div class="auth-bottom">
        {{ __('Belum punya akun?') }}
        <a href="{{ route('register') }}">{{ __('Registrasi') }}</a>
    </div>
</x-guest-layout>
