<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Spiritual') }}
            </h2>

            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <label for="range" class="text-sm text-gray-600">{{ __('Rentang') }}</label>
                <select id="range" name="range" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="7" @selected($range === 7)>{{ __('7 hari') }}</option>
                    <option value="30" @selected($range === 30)>{{ __('30 hari') }}</option>
                </select>
                <x-secondary-button type="submit">{{ __('Tampilkan') }}</x-secondary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'radar-saved')
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __('Cek harian tersimpan.') }}
                </div>
            @endif

            @if (session('status') === 'journal-saved')
                <div class="rounded-md bg-blue-50 p-4 text-sm text-blue-700">
                    {{ __('Catatan reflektif tersimpan.') }}
                </div>
            @endif

            @if (! $hasToday)
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-700 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <span>{{ __('Anda belum mengisi cek harian hari ini.') }}</span>
                    <a href="#daily-check" class="text-sm font-medium text-amber-700 underline">
                        {{ __('Isi sekarang') }}
                    </a>
                </div>
            @endif

            @if ($errors->has('daily'))
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-700">
                    {{ $errors->first('daily') }}
                </div>
            @endif

            @if ($errors->has('journal'))
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-700">
                    {{ $errors->first('journal') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Ringkasan Hari Ini') }}</h3>

                    @if ($latestLog)
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __('Terakhir diisi:') }} {{ $latestLog->date->format('d M Y') }}
                        </p>

                        @if ($summaryText)
                            <p class="mt-2 text-gray-800">{{ $summaryText }}</p>
                        @endif

                        @if ($trendText)
                            <p class="mt-1 text-sm text-gray-600">{{ $trendText }}</p>
                        @endif

                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3 text-sm text-gray-700">
                            <div class="rounded-lg border border-gray-100 p-3">
                                <span class="font-medium">{{ __('Makna hidup') }}</span>
                                <span class="float-right">{{ $latestLog->score_meaning }}/5</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <span class="font-medium">{{ __('Kedekatan dengan Tuhan/yang Ilahi') }}</span>
                                <span class="float-right">{{ $latestLog->score_closeness }}/5</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <span class="font-medium">{{ __('Rasa damai') }}</span>
                                <span class="float-right">{{ $latestLog->score_peace }}/5</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <span class="font-medium">{{ __('Rasa takut') }}</span>
                                <span class="float-right">{{ $latestLog->score_fear }}/5</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <span class="font-medium">{{ __('Rasa kesepian') }}</span>
                                <span class="float-right">{{ $latestLog->score_loneliness }}/5</span>
                            </div>
                        </div>

                        @if ($latestLog->symptoms)
                            <p class="mt-4 text-sm text-gray-600">
                                <span class="font-medium text-gray-900">{{ __('Gejala/keluhan utama:') }}</span>
                                {{ $latestLog->symptoms }}
                            </p>
                        @endif
                    @else
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __('Belum ada data. Silakan isi cek harian pertama Anda.') }}
                        </p>
                    @endif

                    @if (! empty($recommendations))
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900">{{ __('Saran langkah kecil') }}</h4>
                            <ul class="mt-2 list-disc list-inside text-sm text-gray-700 space-y-1">
                                @foreach ($recommendations as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg" id="daily-check">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Cek Harian') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Isi skala 1-5. 1 = sangat rendah, 5 = sangat baik.') }}
                    </p>

                    @if ($hasToday)
                        <p class="mt-3 text-sm text-green-700">
                            {{ __('Anda sudah mengisi cek harian hari ini.') }}
                        </p>
                    @else
                        <form method="POST" action="{{ route('dashboard.radar.store') }}" class="mt-4 space-y-6">
                            @csrf

                            @php
                                $questions = [
                                    'score_meaning' => 'Makna hidup',
                                    'score_closeness' => 'Kedekatan dengan Tuhan/yang Ilahi',
                                    'score_peace' => 'Rasa damai',
                                    'score_fear' => 'Rasa takut terhadap masa depan/kematian',
                                    'score_loneliness' => 'Rasa kesepian',
                                ];
                            @endphp

                            @foreach ($questions as $field => $label)
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $label }}</p>
                                    <div class="mt-2 flex flex-wrap gap-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="inline-flex items-center gap-2">
                                                <input
                                                    type="radio"
                                                    name="{{ $field }}"
                                                    value="{{ $i }}"
                                                    class="text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                    @checked(old($field) == $i)
                                                    @if ($i === 1) required @endif
                                                >
                                                <span class="text-sm text-gray-700">{{ $i }}</span>
                                            </label>
                                        @endfor
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get($field)" />
                                </div>
                            @endforeach

                            <div>
                                <x-input-label for="symptoms" :value="__('Gejala/keluhan utama (opsional)')" />
                                <textarea id="symptoms" name="symptoms" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('symptoms') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('symptoms')" />
                            </div>

                            <div class="flex items-center gap-3">
                                <x-primary-button>{{ __('Simpan') }}</x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg" id="journal">
                <div class="p-6">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Jurnal Reflektif') }}</h3>
                        <a href="{{ route('journals.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            {{ __('Lihat semua catatan') }}
                        </a>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Tuliskan perasaan, harapan, atau doa hari ini. Anda bisa mengizinkan tenaga kesehatan membaca catatan ini.') }}
                    </p>

                    @if ($hasJournalToday)
                        <p class="mt-3 text-sm text-green-700">
                            {{ __('Catatan reflektif hari ini sudah dibuat.') }}
                        </p>
                    @else
                        <form method="POST" action="{{ route('journals.store') }}" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="journal_content" :value="__('Catatan hari ini')" />
                                <textarea id="journal_content" name="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')" />
                            </div>

                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_shareable" value="1" class="rounded border-gray-300 text-indigo-600" @checked(old('is_shareable'))>
                                {{ __('Izinkan tenaga kesehatan membaca catatan ini') }}
                            </label>

                            <div>
                                <x-primary-button>{{ __('Simpan catatan') }}</x-primary-button>
                            </div>
                        </form>
                    @endif

                    @if ($journalEntries->isNotEmpty())
                        <div class="mt-6 space-y-4">
                            @foreach ($journalEntries as $entry)
                                <div class="rounded-lg border border-gray-100 p-4">
                                    <p class="text-xs text-gray-500">{{ $entry->entry_date->format('d M Y') }}</p>
                                    <p class="mt-2 text-sm text-gray-700">{{ $entry->content }}</p>

                                    @if ($entry->provider_response)
                                        <div class="mt-3 rounded-md bg-slate-50 p-3 text-sm text-slate-700">
                                            <p class="font-medium text-slate-900">{{ __('Respon tenaga kesehatan') }}</p>
                                            <p class="mt-1">{{ $entry->provider_response }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-600">
                            {{ __('Belum ada catatan reflektif.') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ __('Tren') }} {{ $range }} {{ __('Hari') }}
                    </h3>

                    @if (! empty($chartData['labels']))
                        <div class="mt-4 h-72">
                            <canvas id="spiritualTrendChart" class="w-full h-full"></canvas>
                        </div>
                    @else
                        <p class="mt-2 text-sm text-gray-600">
                            {{ __('Belum cukup data untuk menampilkan grafik.') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (! empty($chartData['labels']))
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const labels = @json($chartData['labels']);
            const datasets = [
                {
                    label: 'Makna hidup',
                    data: @json($chartData['datasets']['meaning']),
                    borderColor: '#1d4ed8',
                    backgroundColor: 'rgba(29, 78, 216, 0.1)',
                    tension: 0.3,
                },
                {
                    label: 'Kedekatan dengan Tuhan/yang Ilahi',
                    data: @json($chartData['datasets']['closeness']),
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(15, 118, 110, 0.1)',
                    tension: 0.3,
                },
                {
                    label: 'Rasa damai',
                    data: @json($chartData['datasets']['peace']),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    tension: 0.3,
                },
                {
                    label: 'Rasa takut',
                    data: @json($chartData['datasets']['fear']),
                    borderColor: '#b91c1c',
                    backgroundColor: 'rgba(185, 28, 28, 0.1)',
                    tension: 0.3,
                },
                {
                    label: 'Rasa kesepian',
                    data: @json($chartData['datasets']['loneliness']),
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.3,
                },
            ];

            const ctx = document.getElementById('spiritualTrendChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets,
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                min: 1,
                                max: 5,
                                ticks: {
                                    stepSize: 1,
                                },
                            },
                        },
                    },
                });
            }
        </script>
    @endif
</x-app-layout>
