<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Moderasi Jurnal') }}
            </h2>

            <form method="GET" action="{{ route('admin.journals.index') }}" class="flex flex-wrap items-center gap-2">
                <label for="visibility_filter" class="text-sm text-gray-600">{{ __('Tampilkan') }}</label>
                <select id="visibility_filter" name="visibility" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="shareable" @selected($visibility === 'shareable')>{{ __('Hanya yang disetujui') }}</option>
                    <option value="all" @selected($visibility === 'all')>{{ __('Semua') }}</option>
                </select>
                <x-secondary-button type="submit">{{ __('Terapkan') }}</x-secondary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'journal-responded')
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ __('Respon tersimpan.') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="text-xs uppercase text-gray-500">
                            <tr>
                                <th class="py-2 pe-4">{{ __('Tanggal') }}</th>
                                <th class="py-2 pe-4">{{ __('Pengguna') }}</th>
                                <th class="py-2 pe-4">{{ __('Catatan') }}</th>
                                <th class="py-2 pe-4">{{ __('Status') }}</th>
                                <th class="py-2 pe-4">{{ __('Respon') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($entries as $entry)
                                <tr>
                                    <td class="py-3 pe-4 text-gray-600">{{ $entry->entry_date->format('d M Y') }}</td>
                                    <td class="py-3 pe-4">
                                        <div class="text-gray-900">{{ $entry->user?->name ?? __('Pengguna') }}</div>
                                        <div class="text-xs text-gray-500">{{ $entry->user?->email }}</div>
                                    </td>
                                    <td class="py-3 pe-4 text-gray-600">{{ $entry->content }}</td>
                                    <td class="py-3 pe-4">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $entry->is_shareable ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $entry->is_shareable ? __('Disetujui') : __('Privat') }}
                                        </span>
                                    </td>
                                    <td class="py-3 pe-4">
                                        @if ($entry->is_shareable)
                                            <form method="POST" action="{{ route('admin.journals.update', $entry) }}" class="space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <textarea name="provider_response" rows="3" class="w-64 rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('provider_response', $entry->provider_response) }}</textarea>
                                                <div>
                                                    <x-secondary-button type="submit">{{ __('Simpan respon') }}</x-secondary-button>
                                                </div>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-500">{{ __('Tidak tersedia') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-500">
                                        {{ __('Belum ada catatan yang dapat dimoderasi.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $entries->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
