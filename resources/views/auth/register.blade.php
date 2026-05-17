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
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <label for="password_confirmation">Konfirmasi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
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
