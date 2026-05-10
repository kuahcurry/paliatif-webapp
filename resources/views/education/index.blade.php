<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modul Edukasi Caregiver') }}
            </h2>

            <form method="GET" action="{{ route('education.index') }}" class="flex flex-wrap items-center gap-2">
                <label for="tag_filter" class="text-sm text-gray-600">{{ __('Filter') }}</label>
                <select id="tag_filter" name="tag" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" @selected($tag === 'all')>{{ __('Semua topik') }}</option>
                    <option value="coping" @selected($tag === 'coping')>{{ __('Coping dan tenang') }}</option>
                    <option value="communication" @selected($tag === 'communication')>{{ __('Komunikasi') }}</option>
                    <option value="grief" @selected($tag === 'grief')>{{ __('Manajemen duka') }}</option>
                    <option value="spiritual_support" @selected($tag === 'spiritual_support')>{{ __('Dukungan spiritual') }}</option>
                </select>
                <x-secondary-button type="submit">{{ __('Terapkan') }}</x-secondary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (! $hasAssessment)
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-800">
                    {{ __('Rekomendasi akan lebih tepat jika pengkajian caregiver sudah diisi.') }}
                    <a class="underline font-medium" href="{{ route('menu.assessment') }}">
                        {{ __('Isi pengkajian sekarang') }}
                    </a>
                </div>
            @endif

            @if ($recommendedModules->isNotEmpty())
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Rekomendasi untuk Anda') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Dipilih berdasarkan pengkajian caregiver terakhir.') }}
                        </p>

                        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($recommendedModules as $module)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <div class="text-xs text-indigo-600">{{ strtoupper($module->type) }}</div>
                                    <h4 class="mt-1 font-semibold text-gray-900">{{ $module->title }}</h4>
                                    <p class="mt-2 text-sm text-gray-600">{{ $module->summary }}</p>

                                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                                        @foreach ($module->tags ?? [] as $tagItem)
                                            <span class="rounded-full bg-slate-100 px-2 py-1">{{ str_replace('_', ' ', $tagItem) }}</span>
                                        @endforeach
                                    </div>

                                    @if ($module->url)
                                        <a class="mt-4 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ $module->url }}" target="_blank" rel="noopener">
                                            {{ __('Buka konten') }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Library konten') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Artikel dan video pendek untuk membantu caregiver mendampingi pasien.') }}
                    </p>

                    @if ($modules->isEmpty())
                        <p class="mt-4 text-sm text-gray-600">{{ __('Belum ada konten edukasi.') }}</p>
                    @else
                        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($modules as $module)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <div class="text-xs text-indigo-600">{{ strtoupper($module->type) }}</div>
                                    <h4 class="mt-1 font-semibold text-gray-900">{{ $module->title }}</h4>
                                    <p class="mt-2 text-sm text-gray-600">{{ $module->summary }}</p>

                                    @if ($module->content)
                                        <p class="mt-2 text-xs text-gray-500">
                                            {{ \Illuminate\Support\Str::limit($module->content, 140) }}
                                        </p>
                                    @endif

                                    <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600">
                                        @foreach ($module->tags ?? [] as $tagItem)
                                            <span class="rounded-full bg-slate-100 px-2 py-1">{{ str_replace('_', ' ', $tagItem) }}</span>
                                        @endforeach
                                    </div>

                                    @if ($module->url)
                                        <a class="mt-4 inline-flex text-sm font-medium text-indigo-600 hover:text-indigo-500" href="{{ $module->url }}" target="_blank" rel="noopener">
                                            {{ __('Buka konten') }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
