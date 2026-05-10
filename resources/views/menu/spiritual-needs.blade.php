<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kebutuhan Spiritual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Pohon Spiritual') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Pohon spiritual akan terus bertumbuh seiring perjalanan pengguna.') }}
                    </p>

                    <div class="mt-4 rounded-xl border border-dashed border-green-200 bg-green-50 p-6 text-sm text-green-800">
                        <p class="font-medium">{{ __('Harapan dan motivasi harian') }}</p>
                        <p class="mt-2">
                            {{ __('Konten berupa video dengan narasi teks akan muncul sesuai hasil pengkajian.') }}
                        </p>
                        <p class="mt-2">
                            {{ __('Rekomendasi akan disesuaikan dengan kebutuhan pengguna agar lebih relevan.') }}
                        </p>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-600">
                                {{ __('Butuh panduan bagi caregiver?') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ __('Akses modul edukasi spiritual-psikososial yang direkomendasikan.') }}
                            </p>
                        </div>
                        <a href="{{ route('education.index') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500">
                            {{ __('Buka modul edukasi') }}
                        </a>
                    </div>

                    <p class="mt-6 text-xs text-gray-500">
                        {{ __('Catatan: Konten layanan spiritual akan disusun bertahap bersama tim pendamping.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
