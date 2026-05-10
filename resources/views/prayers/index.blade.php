<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pohon Doa') }}
            </h2>

            <form method="GET" action="{{ route('prayers.index') }}" class="flex flex-wrap items-center gap-2">
                <label for="category_filter" class="text-sm text-gray-600">{{ __('Kategori') }}</label>
                <select id="category_filter" name="category" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" @selected($category === 'all')>{{ __('Semua') }}</option>
                    <option value="self" @selected($category === 'self')>{{ __('Untuk diri sendiri') }}</option>
                    <option value="others" @selected($category === 'others')>{{ __('Untuk orang lain') }}</option>
                    <option value="gratitude" @selected($category === 'gratitude')>{{ __('Doa syukur') }}</option>
                </select>

                <label for="sort_filter" class="text-sm text-gray-600">{{ __('Urutkan') }}</label>
                <select id="sort_filter" name="sort" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="latest" @selected($sort === 'latest')>{{ __('Terbaru') }}</option>
                    <option value="support" @selected($sort === 'support')>{{ __('Paling didoakan') }}</option>
                </select>

                <x-secondary-button type="submit">{{ __('Terapkan') }}</x-secondary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'prayer-public')
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __('Doa Anda tersimpan dan tampil di pohon.') }}
                </div>
            @endif

            @if (session('status') === 'prayer-private')
                <div class="rounded-md bg-blue-50 p-4 text-sm text-blue-700">
                    {{ __('Doa Anda tersimpan sebagai privat.') }}
                </div>
            @endif

            @if (session('status') === 'support-added')
                <div class="rounded-md bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ __('Terima kasih sudah ikut mendoakan.') }}
                </div>
            @endif

            @if ($errors->has('recaptcha'))
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-700">
                    {{ $errors->first('recaptcha') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Titip Doa') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Tulis doa singkat. Anda bisa menggunakan nama atau anonim.') }}
                    </p>
                    <div class="mt-3 rounded-md bg-slate-50 p-3 text-xs text-slate-600">
                        <p class="font-medium text-slate-700">{{ __('Aturan singkat') }}</p>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            <li>{{ __('Gunakan bahasa yang sopan dan tidak menyinggung pihak lain.') }}</li>
                            <li>{{ __('Hindari informasi pribadi yang sensitif.') }}</li>
                            <li>{{ __('Doa yang melanggar dapat dihapus oleh admin.') }}</li>
                        </ul>
                    </div>

                    <form id="prayer-form" method="POST" action="{{ route('prayers.store') }}" class="mt-4 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="display_name" :value="__('Nama/alias (opsional)')" />
                            <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" :value="old('display_name')" autocomplete="off" />
                            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
                        </div>

                        <div>
                            <x-input-label for="category" :value="__('Kategori doa')" />
                            <select id="category" name="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">{{ __('Pilih kategori') }}</option>
                                <option value="self" @selected(old('category') === 'self')>{{ __('Untuk diri sendiri') }}</option>
                                <option value="others" @selected(old('category') === 'others')>{{ __('Untuk orang lain') }}</option>
                                <option value="gratitude" @selected(old('category') === 'gratitude')>{{ __('Doa syukur') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category')" />
                        </div>

                        <div>
                            <x-input-label for="content" :value="__('Isi doa (maksimal 500 karakter)')" />
                            <textarea id="content" name="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('content')" />
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ __('Tampilkan doa') }}</p>
                            <div class="mt-2 flex flex-wrap gap-4">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="is_public" value="1" class="text-indigo-600 border-gray-300" @checked(old('is_public', '1') === '1')>
                                    {{ __('Publik di pohon doa') }}
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input type="radio" name="is_public" value="0" class="text-indigo-600 border-gray-300" @checked(old('is_public') === '0')>
                                    {{ __('Privat (hanya tersimpan)') }}
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('is_public')" />
                        </div>

                        @if ($recaptchaSiteKey)
                            <input type="hidden" id="recaptcha_token" name="recaptcha_token" value="">
                        @endif

                        <div class="flex items-center gap-3">
                            <x-primary-button>{{ __('Kirim doa') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Daun Doa') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Klik salah satu daun untuk membaca doa singkat dan ikut mendoakan.') }}
                    </p>

                    <div class="prayer-tree mt-6">
                        <div class="tree-trunk"></div>
                        <div class="tree-canopy"></div>

                        <div class="tree-leaves">
                            @php
                                $positions = [
                                    ['x' => 18, 'y' => 12],
                                    ['x' => 35, 'y' => 8],
                                    ['x' => 52, 'y' => 14],
                                    ['x' => 70, 'y' => 10],
                                    ['x' => 25, 'y' => 30],
                                    ['x' => 45, 'y' => 28],
                                    ['x' => 62, 'y' => 30],
                                    ['x' => 78, 'y' => 26],
                                    ['x' => 15, 'y' => 48],
                                    ['x' => 32, 'y' => 46],
                                    ['x' => 50, 'y' => 44],
                                    ['x' => 68, 'y' => 46],
                                    ['x' => 84, 'y' => 42],
                                    ['x' => 22, 'y' => 64],
                                    ['x' => 40, 'y' => 62],
                                    ['x' => 58, 'y' => 62],
                                    ['x' => 76, 'y' => 60],
                                    ['x' => 90, 'y' => 58],
                                ];
                                $categoryLabels = [
                                    'self' => 'Untuk diri sendiri',
                                    'others' => 'Untuk orang lain',
                                    'gratitude' => 'Doa syukur',
                                ];
                            @endphp

                            @forelse ($prayers as $index => $prayer)
                                @php
                                    $position = $positions[$index % count($positions)];
                                    $displayName = $prayer->display_name ?: 'Anonim';
                                    $categoryLabel = $categoryLabels[$prayer->category] ?? 'Doa';
                                @endphp
                                <div class="leaf" style="--leaf-x: {{ $position['x'] }}%; --leaf-y: {{ $position['y'] }}%;">
                                    <details class="leaf-card">
                                        <summary>
                                            <div class="leaf-title">{{ $displayName }}</div>
                                            <div class="leaf-subtitle">{{ $categoryLabel }}</div>
                                        </summary>
                                        <div class="leaf-content">
                                            <p class="text-sm text-gray-700">{{ $prayer->content }}</p>
                                            <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                                                <span>{{ $prayer->support_count }} {{ __('orang ikut mendoakan') }}</span>
                                                <form method="POST" action="{{ route('prayers.support', $prayer) }}">
                                                    @csrf
                                                    <x-secondary-button type="submit">{{ __('Ikut mendoakan') }}</x-secondary-button>
                                                </form>
                                            </div>
                                        </div>
                                    </details>
                                </div>
                            @empty
                                <p class="text-sm text-gray-600">{{ __('Belum ada doa publik. Jadilah yang pertama menitipkan doa.') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .prayer-tree {
            position: relative;
            min-height: 420px;
            background: radial-gradient(circle at 50% 25%, #d9f99d 0%, #f0fdf4 40%, #f8fafc 100%);
            border-radius: 24px;
            overflow: hidden;
        }

        .tree-trunk {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            height: 180px;
            background: linear-gradient(180deg, #8b5e3c 0%, #6b4b32 100%);
            border-radius: 20px 20px 8px 8px;
        }

        .tree-canopy {
            position: absolute;
            top: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 260px;
            background: radial-gradient(circle at 50% 40%, #bbf7d0 0%, #86efac 45%, #4ade80 100%);
            border-radius: 999px;
            opacity: 0.35;
        }

        .tree-leaves {
            position: absolute;
            inset: 0;
        }

        .leaf {
            position: absolute;
            left: var(--leaf-x);
            top: var(--leaf-y);
            transform: translate(-50%, -50%);
        }

        .leaf-card {
            width: 160px;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.3);
            border-radius: 16px;
            padding: 12px 14px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
        }

        .leaf-card summary {
            list-style: none;
            cursor: pointer;
        }

        .leaf-card summary::-webkit-details-marker {
            display: none;
        }

        .leaf-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
        }

        .leaf-subtitle {
            margin-top: 2px;
            font-size: 0.75rem;
            color: #475569;
        }

        .leaf-content {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .prayer-tree {
                min-height: 520px;
            }

            .leaf {
                position: static;
                transform: none;
                margin-top: 12px;
            }

            .tree-trunk,
            .tree-canopy {
                display: none;
            }

            .tree-leaves {
                position: static;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
                gap: 12px;
                padding: 12px 0 24px;
            }
        }
    </style>

    @if ($recaptchaSiteKey)
        <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptchaSiteKey }}"></script>
        <script>
            const prayerForm = document.getElementById('prayer-form');
            const recaptchaField = document.getElementById('recaptcha_token');

            if (prayerForm && recaptchaField) {
                prayerForm.addEventListener('submit', (event) => {
                    if (recaptchaField.value) {
                        return;
                    }

                    event.preventDefault();
                    grecaptcha.ready(() => {
                        grecaptcha.execute('{{ $recaptchaSiteKey }}', { action: 'prayer' }).then((token) => {
                            recaptchaField.value = token;
                            prayerForm.submit();
                        });
                    });
                });
            }
        </script>
    @endif
</x-app-layout>
