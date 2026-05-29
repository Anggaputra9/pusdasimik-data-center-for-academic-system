@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
<div
    x-data="dosenPage({
        storeUrl: '{{ route('admin.dosen.store') }}',
        updateUrlBase: '{{ url('admin/dosen') }}',
        oldNip: '{{ old('nip') }}',
        oldNama: '{{ old('nama') }}',
        autoOpen: {{ $errors->any() ? 'true' : 'false' }},
    })"
    x-init="init()"
    class="space-y-6"
>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Total Dosen', 'value' => $dosens->total(), 'color' => 'bg-blue-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'API Status', 'value' => 'Active', 'color' => 'bg-emerald-500', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Halaman', 'value' => $dosens->currentPage() . '/' . $dosens->lastPage(), 'color' => 'bg-purple-500', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                ['label' => 'Ditampilkan', 'value' => $dosens->count(), 'color' => 'bg-orange-500', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ];
        @endphp
        @foreach($stats as $stat)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-colors duration-200 dark:border-gray-800 dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl {{ $stat['color'] }}">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-colors duration-200 dark:border-gray-800 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Dosen</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola data dosen beserta jabatannya.</p>
            </div>
            <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Dosen
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">NIP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($dosens as $dosen)
                        @php
                            $editPayload = [
                                'nip' => $dosen->nip,
                                'nama' => $dosen->nama,
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $dosen->nip }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $dosen->nama }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button"
                                            @click="openEdit({{ Js::from($editPayload) }})"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.dosen.destroy', $dosen->nip) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data dosen {{ $dosen->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada data dosen</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan dosen pertama untuk memulai.</p>
                                    <button type="button" @click="openCreate()"
                                            class="mt-6 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Dosen
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dosens->hasPages())
            <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6">
                {{ $dosens->links() }}
            </div>
        @endif
    </div>

    <!-- Modal (shared create + edit) -->
    <div x-show="open"
         x-cloak
         @keydown.escape.window="close()"
         class="fixed inset-0 z-50 overflow-y-auto"
         role="dialog"
         aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div x-show="open"
                 x-transition.opacity
                 @click="close()"
                 class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-900/75"
                 aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

            <div x-show="open"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:max-w-lg sm:align-middle">

                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex items-start justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                                x-text="mode === 'edit' ? 'Edit Data Dosen' : 'Tambah Dosen Baru'"></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                               x-text="mode === 'edit' ? 'Perbarui informasi dosen.' : 'Lengkapi data dosen baru.'"></p>
                        </div>
                        <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
                            <input type="text" name="nip" required
                                   x-model="form.nip"
                                   :readonly="mode === 'edit'"
                                   :class="mode === 'edit' ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed' : 'bg-white dark:bg-gray-700'"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="mode === 'edit'">NIP tidak dapat diubah.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <input type="text" name="nama" required
                                   x-model="form.nama"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-3 dark:border-gray-700 dark:bg-gray-700/40">
                        <button type="button" @click="close()"
                                class="inline-flex justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit"
                                class="inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <span x-text="mode === 'edit' ? 'Update' : 'Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function dosenPage(config) {
        return {
            storeUrl: config.storeUrl,
            updateUrlBase: config.updateUrlBase,
            open: false,
            mode: 'create',
            form: { nip: '', nama: '' },

            init() {
                if (config.autoOpen) {
                    this.form.nip = config.oldNip || '';
                    this.form.nama = config.oldNama || '';
                    this.mode = 'create';
                    this.open = true;
                }
            },

            get formAction() {
                return this.mode === 'edit'
                    ? `${this.updateUrlBase}/${encodeURIComponent(this.form.nip)}`
                    : this.storeUrl;
            },

            openCreate() {
                this.mode = 'create';
                this.form = { nip: '', nama: '' };
                this.open = true;
            },

            openEdit(data) {
                this.mode = 'edit';
                this.form = {
                    nip: String(data.nip ?? ''),
                    nama: data.nama ?? '',
                };
                this.open = true;
            },

            close() {
                this.open = false;
            },
        };
    }
</script>
@endpush
@endsection
