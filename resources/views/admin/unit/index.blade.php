@extends('layouts.app')

@section('title', 'Data Unit')

@php
    $fakultasOptions = $units->where('tipe', 'fakultas')->map(fn ($u) => ['id' => $u->id, 'nama' => $u->nama])->values();
@endphp

@section('content')
<div
    x-data="unitPage({
        storeUrl: '{{ route('admin.unit.store') }}',
        updateUrlBase: '{{ url('admin/unit') }}',
        fakultasOptions: {{ Js::from($fakultasOptions) }},
        oldKode: '{{ old('kode') }}',
        oldNama: '{{ old('nama') }}',
        oldTipe: '{{ old('tipe') }}',
        oldParentId: '{{ old('parent_id') }}',
        autoOpen: {{ $errors->any() ? 'true' : 'false' }},
    })"
    x-init="init()"
    class="space-y-6"
>
    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table Card -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-colors duration-200 dark:border-gray-800 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Unit</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola fakultas dan program studi yang menjadi referensi mahasiswa.</p>
            </div>
            <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Unit
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Parent</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($units as $unit)
                        @php
                            $editPayload = [
                                'id' => $unit->id,
                                'kode' => $unit->kode,
                                'nama' => $unit->nama,
                                'tipe' => $unit->tipe,
                                'parent_id' => $unit->parent_id,
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $unit->kode }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $unit->nama }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                    {{ $unit->tipe === 'fakultas'
                                        ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300'
                                        : 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                    {{ ucfirst($unit->tipe) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $unit->parent?->nama ?? '-' }}</td>
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
                                    <form action="{{ route('admin.unit.destroy', $unit) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus unit {{ $unit->nama }}?')">
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
                            <td colspan="5" class="px-6 py-16 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada data unit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
                                x-text="mode === 'edit' ? 'Edit Unit' : 'Tambah Unit Baru'"></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atur kode, nama, dan tipe unit.</p>
                        </div>
                        <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode</label>
                            <input type="text" name="kode" maxlength="10" required
                                   x-model="form.kode"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="nama" maxlength="100" required
                                   x-model="form.nama"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe</label>
                            <select name="tipe" required
                                    x-model="form.tipe"
                                    @change="if (form.tipe !== 'prodi') form.parent_id = ''"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Tipe</option>
                                <option value="fakultas">Fakultas</option>
                                <option value="prodi">Prodi</option>
                            </select>
                        </div>

                        <div x-show="form.tipe === 'prodi'">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fakultas Induk</label>
                            <select name="parent_id"
                                    x-model="form.parent_id"
                                    :required="form.tipe === 'prodi'"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Fakultas</option>
                                <template x-for="f in fakultasOptions" :key="f.id">
                                    <option :value="f.id" x-text="f.nama"></option>
                                </template>
                            </select>
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
    function unitPage(config) {
        return {
            storeUrl: config.storeUrl,
            updateUrlBase: config.updateUrlBase,
            fakultasOptions: config.fakultasOptions,
            open: false,
            mode: 'create',
            editingId: null,
            form: { kode: '', nama: '', tipe: '', parent_id: '' },

            init() {
                if (config.autoOpen) {
                    this.form.kode = config.oldKode || '';
                    this.form.nama = config.oldNama || '';
                    this.form.tipe = config.oldTipe || '';
                    this.form.parent_id = config.oldParentId || '';
                    this.mode = 'create';
                    this.open = true;
                }
            },

            get formAction() {
                return this.mode === 'edit'
                    ? `${this.updateUrlBase}/${this.editingId}`
                    : this.storeUrl;
            },

            openCreate() {
                this.mode = 'create';
                this.editingId = null;
                this.form = { kode: '', nama: '', tipe: '', parent_id: '' };
                this.open = true;
            },

            openEdit(data) {
                this.mode = 'edit';
                this.editingId = data.id;
                this.form = {
                    kode: data.kode ?? '',
                    nama: data.nama ?? '',
                    tipe: data.tipe ?? '',
                    parent_id: data.parent_id != null ? String(data.parent_id) : '',
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
