<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <label for="name">{{ __('Nama/alias') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="religion">{{ __('Agama') }}</label>
            <select id="religion" name="religion">
                <option value="">{{ __('Pilih agama') }}</option>
                <option value="Islam" @selected(old('religion', $user->religion) === 'Islam')>Islam</option>
                <option value="Kristen Protestan" @selected(old('religion', $user->religion) === 'Kristen Protestan')>Kristen Protestan</option>
                <option value="Kristen Katolik" @selected(old('religion', $user->religion) === 'Kristen Katolik')>Kristen Katolik</option>
                <option value="Hindu" @selected(old('religion', $user->religion) === 'Hindu')>Hindu</option>
                <option value="Buddha" @selected(old('religion', $user->religion) === 'Buddha')>Buddha</option>
                <option value="Konghucu" @selected(old('religion', $user->religion) === 'Konghucu')>Konghucu</option>
            </select>
            <x-input-error :messages="$errors->get('religion')" />
        </div>

        <div class="data-diri">
            <h3>{{ __('Data Diri') }}</h3>
            <div class="data-grid">
                <div>
                    <label for="patient_gender">{{ __('Jenis Kelamin') }}</label>
                    <select id="patient_gender" name="patient_gender">
                        <option value="">{{ __('Pilih') }}</option>
                        <option value="Laki-laki" @selected(old('patient_gender', $user->patient_gender) === 'Laki-laki')>{{ __('Laki-laki') }}</option>
                        <option value="Perempuan" @selected(old('patient_gender', $user->patient_gender) === 'Perempuan')>{{ __('Perempuan') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('patient_gender')" />
                </div>
                <div>
                    <label for="patient_birth_date">{{ __('Tanggal Lahir') }}</label>
                    <input id="patient_birth_date" name="patient_birth_date" type="date" value="{{ old('patient_birth_date', $user->patient_birth_date?->format('Y-m-d')) }}" />
                    <x-input-error :messages="$errors->get('patient_birth_date')" />
                </div>
                <div>
                    <label for="marital_status">{{ __('Status Pernikahan') }}</label>
                    <select id="marital_status" name="marital_status">
                        <option value="">{{ __('Pilih') }}</option>
                        <option value="Menikah" @selected(old('marital_status', $user->marital_status) === 'Menikah')>Menikah</option>
                        <option value="Belum Menikah" @selected(old('marital_status', $user->marital_status) === 'Belum Menikah')>Belum Menikah</option>
                        <option value="Cerai" @selected(old('marital_status', $user->marital_status) === 'Cerai')>Cerai</option>
                        <option value="Duda/Janda" @selected(old('marital_status', $user->marital_status) === 'Duda/Janda')>Duda/Janda</option>
                    </select>
                    <x-input-error :messages="$errors->get('marital_status')" />
                </div>
            </div>
        </div>

        <div>
            <label for="email">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="verify-note">
                        {{ __('Email Anda belum terverifikasi.') }}
                        <button form="send-verification" class="verify-link">{{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="verify-sent">{{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit">{{ __('Simpan') }}</button>
            @if (session('status') === 'profile-updated')
                <span class="saved-notice"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                >{{ __('Tersimpan.') }}</span>
            @endif
        </div>
    </form>
</section>
