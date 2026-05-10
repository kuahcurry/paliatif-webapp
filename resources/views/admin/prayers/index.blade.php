<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Moderasi Doa') }}
            </h2>

            <form method="GET" action="{{ route('admin.prayers.index') }}" class="flex flex-wrap items-center gap-2">
                <label for="visibility_filter" class="text-sm text-gray-600">{{ __('Status') }}</label>
                <select id="visibility_filter" name="visibility" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" @selected($visibility === 'all')>{{ __('Semua') }}</option>
                    <option value="public" @selected($visibility === 'public')>{{ __('Publik') }}</option>
                    <option value="private" @selected($visibility === 'private')>{{ __('Privat') }}</option>
                </select>

                <label for="category_filter" class="text-sm text-gray-600">{{ __('Kategori') }}</label>
                <select id="category_filter" name="category" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="all" @selected($category === 'all')>{{ __('Semua') }}</option>
                    <option value="self" @selected($category === 'self')>{{ __('Untuk diri sendiri') }}</option>
                    <option value="others" @selected($category === 'others')>{{ __('Untuk orang lain') }}</option>
                    <option value="gratitude" @selected($category === 'gratitude')>{{ __('Doa syukur') }}</option>
                </select>

                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="{{ __('Cari kata kunci') }}"
                    class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >

                <x-secondary-button type="submit">{{ __('Terapkan') }}</x-secondary-button>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'prayer-deleted')
                <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-700">
                    {{ __('Doa berhasil dihapus.') }}
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="text-xs uppercase text-gray-500">
                            <tr>
                                <th class="py-2 pe-4">{{ __('Tanggal') }}</th>
                                <th class="py-2 pe-4">{{ __('Nama/alias') }}</th>
                                <th class="py-2 pe-4">{{ __('Kategori') }}</th>
                                <th class="py-2 pe-4">{{ __('Status') }}</th>
                                <th class="py-2 pe-4">{{ __('Dukungan') }}</th>
                                <th class="py-2 pe-4">{{ __('Isi singkat') }}</th>
                                <th class="py-2 pe-4">{{ __('Aksi') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($prayers as $prayer)
                                <tr>
                                    <td class="py-3 pe-4 text-gray-600">{{ $prayer->created_at->format('d M Y') }}</td>
                                    <td class="py-3 pe-4">
                                        {{ $prayer->display_name ?: ($prayer->user?->name ?? __('Anonim')) }}
                                    </td>
                                    <td class="py-3 pe-4 text-gray-600">
                                        @if ($prayer->category === 'self')
                                            {{ __('Untuk diri sendiri') }}
                                        @elseif ($prayer->category === 'others')
                                            {{ __('Untuk orang lain') }}
                                        @else
                                            {{ __('Doa syukur') }}
                                        @endif
                                    </td>
                                    <td class="py-3 pe-4">
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $prayer->is_public ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $prayer->is_public ? __('Publik') : __('Privat') }}
                                        </span>
                                    </td>
                                    <td class="py-3 pe-4 text-gray-600">{{ $prayer->support_count }}</td>
                                    <td class="py-3 pe-4 text-gray-600">
                                        {{ \Illuminate\Support\Str::limit($prayer->content, 80) }}
                                    </td>
                                    <td class="py-3 pe-4">
                                        <form method="POST" action="{{ route('admin.prayers.destroy', $prayer) }}" onsubmit="return confirm('{{ __('Yakin akan menghapus doa ini?') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">{{ __('Hapus') }}</x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-gray-500">
                                        {{ __('Belum ada doa yang masuk.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                {{ $prayers->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
