<x-guest-layout>
    <div class="auth-form-header">
        <h2>Verifikasi Email</h2>
        <p>Satu langkah lagi sebelum memulai</p>
    </div>

    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="width: 56px; height: 56px; background: #e5f6e5; border-radius: 16px; display: grid; place-items: center; margin: 0 auto 16px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 28px; height: 28px;"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            </div>
            <p style="font-size: 0.88rem; color: #334155; line-height: 1.6;">
                {{ __('Terima kasih telah mendaftar! Silakan verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan.') }}
            </p>
            <p style="font-size: 0.82rem; color: #64748b; margin-top: 8px;">
                {{ __('Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan yang baru.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div style="background: #ecfdf3; color: #15803d; padding: 12px 16px; border-radius: 12px; font-size: 0.82rem; font-weight: 500; margin-bottom: 16px; text-align: center;">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
            </div>
        @endif

        <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
            <form method="POST" action="{{ route('verification.send') }}" style="flex: 1;">
                @csrf
                <button type="submit" style="width: 100%;">
                    {{ __('Kirim Ulang Email Verifikasi') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background: none; box-shadow: none; color: #64748b; font-weight: 600; font-size: 0.82rem; padding: 11px 16px;">
                    {{ __('Keluar') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
