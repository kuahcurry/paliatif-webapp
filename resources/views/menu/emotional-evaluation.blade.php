<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evaluasi Perasaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Evaluasi setelah layanan') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Pilih kondisi yang paling sesuai setelah menggunakan layanan kebutuhan spiritual.') }}
                    </p>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div class="rounded-lg border border-gray-200 p-4 text-center">
                            <span class="text-3xl">🙂</span>
                            <p class="mt-2 text-sm text-gray-700">{{ __('Lebih tenang') }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-4 text-center">
                            <span class="text-3xl">😐</span>
                            <p class="mt-2 text-sm text-gray-700">{{ __('Netral') }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-4 text-center">
                            <span class="text-3xl">😕</span>
                            <p class="mt-2 text-sm text-gray-700">{{ __('Masih cemas') }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-4 text-center">
                            <span class="text-3xl">😢</span>
                            <p class="mt-2 text-sm text-gray-700">{{ __('Butuh dukungan lanjut') }}</p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        <p class="font-medium">{{ __('Batas penggunaan') }}</p>
                        <p class="mt-2">
                            {{ __('Pengguna dapat menggunakan layanan evaluasi hingga 5 kali.') }}
                            {{ __('Jika tidak ada perubahan dan membutuhkan layanan lanjut, pada evaluasi ke-5 pengguna diarahkan ke layanan profesional.') }}
                        </p>
                    </div>

                    <p class="mt-6 text-xs text-gray-500">
                        {{ __('Catatan: Alur evaluasi akan dihubungkan dengan layanan profesional pada tahap berikutnya.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
