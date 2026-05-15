<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Perbarui informasi profil dan alamat email akun Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama/alias')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="religion" :value="__('Agama (opsional)')" />
            <x-text-input id="religion" name="religion" type="text" class="mt-1 block w-full" :value="old('religion', $user->religion)" autocomplete="off" />
            <x-input-error class="mt-2" :messages="$errors->get('religion')" />
        </div>

        <div class="border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Data Pasien') }}</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="patient_gender" :value="__('Jenis Kelamin')" />
                    <select id="patient_gender" name="patient_gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">{{ __('Pilih') }}</option>
                        <option value="Laki-laki" @selected(old('patient_gender', $user->patient_gender) === 'Laki-laki')>{{ __('Laki-laki') }}</option>
                        <option value="Perempuan" @selected(old('patient_gender', $user->patient_gender) === 'Perempuan')>{{ __('Perempuan') }}</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('patient_gender')" />
                </div>
                <div>
                    <x-input-label for="patient_age" :value="__('Usia (tahun)')" />
                    <x-text-input id="patient_age" name="patient_age" type="number" min="0" max="150" class="mt-1 block w-full" :value="old('patient_age', $user->patient_age)" />
                    <x-input-error class="mt-2" :messages="$errors->get('patient_age')" />
                </div>
                <div>
                    <x-input-label for="patient_rm" :value="__('No. RM')" />
                    <x-text-input id="patient_rm" name="patient_rm" type="text" class="mt-1 block w-full" :value="old('patient_rm', $user->patient_rm)" />
                    <x-input-error class="mt-2" :messages="$errors->get('patient_rm')" />
                </div>
                <div>
                    <x-input-label for="patient_room" :value="__('Ruang')" />
                    <x-text-input id="patient_room" name="patient_room" type="text" class="mt-1 block w-full" :value="old('patient_room', $user->patient_room)" />
                    <x-input-error class="mt-2" :messages="$errors->get('patient_room')" />
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Email Anda belum terverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
