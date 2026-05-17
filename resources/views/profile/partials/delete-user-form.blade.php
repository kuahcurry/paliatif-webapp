<section class="space-y-6">
    <header>
        <h2>{{ __('Hapus Akun') }}</h2>
        <p>{{ __('Setelah akun dihapus, semua data akan hilang secara permanen. Pastikan Anda menyimpan informasi yang masih dibutuhkan sebelum melanjutkan.') }}</p>
    </header>

    <button
        type="button"
        class="danger-btn"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Hapus Akun') }}</button>

    <div
        x-data="{
            show: false,
            focusables() {
                let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
                return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
            },
            firstFocusable() { return this.focusables()[0] },
            lastFocusable() { return this.focusables().slice(-1)[0] },
        }"
        x-init="$watch('show', value => { if (value) { document.body.classList.add('overflow-hidden'); setTimeout(() => firstFocusable()?.focus(), 100) } else { document.body.classList.remove('overflow-hidden') } })"
        x-on:open-modal.window="$event.detail == 'confirm-user-deletion' ? show = true : null"
        x-on:close-modal.window="$event.detail == 'confirm-user-deletion' ? show = false : null"
        x-on:close.stop="show = false"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        class="confirm-overlay"
        style="display: none;"
    >
        <div x-show="show" class="confirm-backdrop" x-on:click="show = false"></div>

        <div
            x-show="show"
            class="confirm-dialog"
        >
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h3>{{ __('Apakah Anda yakin ingin menghapus akun?') }}</h3>
                <p>{{ __('Masukkan kata sandi untuk konfirmasi penghapusan akun secara permanen.') }}</p>

                <div class="confirm-password">
                    <label for="delete-password">{{ __('Kata sandi') }}</label>
                    <input
                        id="delete-password"
                        name="password"
                        type="password"
                        placeholder="{{ __('Kata sandi') }}"
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" />
                </div>

                <div class="confirm-actions">
                    <button type="button" class="ghost-button" x-on:click="$dispatch('close')">{{ __('Batal') }}</button>
                    <button type="submit" class="danger-btn">{{ __('Hapus Akun') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
