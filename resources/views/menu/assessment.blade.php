<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengkajian Awal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'assessment-saved')
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __('Pengkajian caregiver tersimpan.') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Instrumen Pengkajian') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Pilih salah satu instrumen untuk memulai pengkajian awal.') }}
                    </p>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="font-semibold text-gray-900">{{ __('Spiritual Well Being Scale (SWBS)') }}</h4>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ __('Mengukur kondisi spiritual secara menyeluruh.') }}
                            </p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="font-semibold text-gray-900">{{ __('Eastern Cooperative Oncology Group (ECOG)') }}</h4>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ __('Menilai kemampuan aktivitas dan kemandirian.') }}
                            </p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="font-semibold text-gray-900">{{ __('Edmonton Symptom Assessment System (ESAS)') }}</h4>
                            <p class="mt-2 text-sm text-gray-600">
                                {{ __('Memetakan gejala utama yang dirasakan pengguna.') }}
                            </p>
                        </div>
                    </div>

                    <p class="mt-6 text-xs text-gray-500">
                        {{ __('Catatan: Konten pengkajian akan ditambahkan sesuai kebutuhan layanan.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Pengkajian Caregiver Singkat') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('Isi skala 1-5 untuk membantu rekomendasi modul edukasi.') }}
                    </p>

                    @if ($latestAssessment)
                        <p class="mt-2 text-xs text-gray-500">
                            {{ __('Pengkajian terakhir:') }} {{ $latestAssessment->created_at->format('d M Y') }}
                        </p>
                    @endif

                    <form method="POST" action="{{ route('menu.assessment.store') }}" class="mt-4 space-y-4">
                        @csrf

                        @php
                            $assessmentQuestions = [
                                'anxiety_level' => 'Tingkat kecemasan caregiver',
                                'grief_level' => 'Tingkat duka yang dirasakan',
                                'communication_level' => 'Kesulitan berkomunikasi dengan pasien/keluarga',
                            ];
                        @endphp

                        @foreach ($assessmentQuestions as $field => $label)
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $label }}</p>
                                <div class="mt-2 flex flex-wrap gap-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input
                                                type="radio"
                                                name="{{ $field }}"
                                                value="{{ $i }}"
                                                class="text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                @checked(old($field) == $i)
                                                @if ($i === 1) required @endif
                                            >
                                            <span>{{ $i }}</span>
                                        </label>
                                    @endfor
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get($field)" />
                            </div>
                        @endforeach

                        <div>
                            <x-primary-button>{{ __('Simpan pengkajian') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
