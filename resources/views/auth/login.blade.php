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
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
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
