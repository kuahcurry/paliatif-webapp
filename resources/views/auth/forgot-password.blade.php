<x-guest-layout>
    <div class="auth-form-header">
        <h2>Lupa Kata Sandi</h2>
        <p>Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="auth-card">
        <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 20px; line-height: 1.6;">
            {{ __('Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.') }}
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-6" style="text-align: center;">
                <button type="submit" style="width: 100%;">
                    {{ __('Kirim Tautan Reset') }}
                </button>
            </div>
        </form>
    </div>

    <div class="auth-bottom">
        {{ __('Ingat kata sandi Anda?') }}
        <a href="{{ route('login') }}">{{ __('Kembali ke Masuk') }}</a>
    </div>
</x-guest-layout>
