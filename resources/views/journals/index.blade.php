<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jurnal Reflektif') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'journal-saved')
                <div class="rounded-md bg-blue-50 p-4 text-sm text-blue-700">
                    {{ __('Catatan reflektif tersimpan.') }}
                </div>
            @endif

            @if ($errors->has('journal'))
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-700">
                    {{ $errors->first('journal') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Tulis catatan hari ini') }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Tulis perasaan, harapan, atau doa yang ingin disimpan.') }}
                    </p>

                    @if ($hasToday)
                        <p class="mt-3 text-sm text-green-700">
                            {{ __('Catatan reflektif hari ini sudah dibuat.') }}
                        </p>
                    @else
                        <form method="POST" action="{{ route('journals.store') }}" class="mt-4 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="content" :value="__('Catatan')" />
                                <textarea id="content" name="content" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
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
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Riwayat catatan') }}</h3>

                    @if ($entries->isEmpty())
                        <p class="mt-2 text-sm text-gray-600">{{ __('Belum ada catatan reflektif.') }}</p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach ($entries as $entry)
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

                        <div class="mt-6">
                            {{ $entries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
