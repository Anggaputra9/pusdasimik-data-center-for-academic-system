@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@php
    $fakultasJson = $fakultas->map(fn ($f) => [
        'id' => $f->id,
        'nama' => $f->nama,
        'prodi' => $f->children->map(fn ($p) => ['id' => $p->id, 'nama' => $p->nama])->values(),
    ])->values();
@endphp

@section('content')
<div
    x-data="mahasiswaPage({
        fakultas: {{ Js::from($fakultasJson) }},
        storeUrl: '{{ route('admin.mahasiswa.store') }}',
        updateUrlBase: '{{ url('admin/mahasiswa') }}',
        defaultTahunAkademik: '2025/2026 Genap',
        oldNim: '{{ old('nim') }}',
        oldNama: '{{ old('nama') }}',
        oldFakultasId: '{{ old('fakultas_id') }}',
        oldUnitId: '{{ old('unit_id') }}',
        oldTahunAkademik: '{{ old('tahun_akademik') }}',
        oldStatus: '{{ old('status') }}',
        autoOpen: {{ $errors->any() ? 'true' : 'false' }},
    })"
    x-init="init()"
    class="space-y-6"
>
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Total Mahasiswa', 'value' => $mahasiswas->total(), 'color' => 'bg-blue-500', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Fakultas', 'value' => $fakultas->count(), 'color' => 'bg-purple-500', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
                ['label' => 'Program Studi', 'value' => $fakultas->sum(fn ($f) => $f->children->count()), 'color' => 'bg-emerald-500', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Halaman', 'value' => $mahasiswas->currentPage() . '/' . $mahasiswas->lastPage(), 'color' => 'bg-orange-500', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
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
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar Mahasiswa</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola data mahasiswa beserta fakultas dan program studinya.</p>
            </div>
            <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Mahasiswa
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Program Studi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Fakultas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Tahun Akademik</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                    @forelse($mahasiswas as $mahasiswa)
                        @php
                            $editPayload = [
                                'nim' => $mahasiswa->nim,
                                'nama' => $mahasiswa->nama,
                                'unit_id' => $mahasiswa->unit_id,
                                'fakultas_id' => optional($mahasiswa->unit)->parent_id,
                                'tahun_akademik' => $mahasiswa->tahun_akademik,
                                'status' => $mahasiswa->status,
                            ];
                            $statusBadge = [
                                'aktif' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
                                'cuti' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300',
                                'lulus' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
                                'do' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
                            ][$mahasiswa->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $mahasiswa->nim }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $mahasiswa->nama }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ optional($mahasiswa->unit)->nama ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ optional(optional($mahasiswa->unit)->parent)->nama ?? '-' }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $mahasiswa->tahun_akademik }}</td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusBadge }}">
                                    {{ $mahasiswa->status_label }}
                                </span>
                            </td>
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
                                    <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->nim) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus data mahasiswa {{ $mahasiswa->nama }}?')">
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
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada data mahasiswa</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan mahasiswa pertama.</p>
                                    <button type="button" @click="openCreate()"
                                            class="mt-6 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Tambah Mahasiswa
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mahasiswas->hasPages())
            <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6">
                {{ $mahasiswas->links() }}
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
                                x-text="mode === 'edit' ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa Baru'"></h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                               x-text="mode === 'edit' ? 'Perbarui informasi mahasiswa.' : 'Lengkapi data mahasiswa baru.'"></p>
                        </div>
                        <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIM</label>
                            <input type="text" name="nim" required
                                   x-model="form.nim"
                                   :readonly="mode === 'edit'"
                                   :class="mode === 'edit' ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed' : 'bg-white dark:bg-gray-700'"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:text-white">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="mode === 'edit'">NIM tidak dapat diubah.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                            <input type="text" name="nama" required
                                   x-model="form.nama"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fakultas</label>
                            <select name="fakultas_id" required
                                    x-model="form.fakultas_id"
                                    @change="form.unit_id = ''"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">Pilih Fakultas</option>
                                <template x-for="f in fakultas" :key="f.id">
                                    <option :value="f.id" x-text="f.nama"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Studi</label>
                            <select name="unit_id" required
                                    x-model="form.unit_id"
                                    :disabled="!form.fakultas_id"
                                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:disabled:bg-gray-800">
                                <option value="">Pilih Program Studi</option>
                                <template x-for="p in prodiList" :key="p.id">
                                    <option :value="p.id" x-text="p.nama"></option>
                                </template>
                            </select>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="!form.fakultas_id">Pilih fakultas terlebih dahulu.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Akademik</label>
                                <input type="text" name="tahun_akademik" required maxlength="20"
                                       x-model="form.tahun_akademik"
                                       placeholder="2025/2026 Genap"
                                       class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status Keaktifan</label>
                                <select name="status" required
                                        x-model="form.status"
                                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="aktif">Aktif</option>
                                    <option value="cuti">Cuti</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="do">Drop Out</option>
                                </select>
                            </div>
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
    function mahasiswaPage(config) {
        const emptyForm = () => ({
            nim: '', nama: '', fakultas_id: '', unit_id: '',
            tahun_akademik: config.defaultTahunAkademik || '',
            status: 'aktif',
        });
        return {
            fakultas: config.fakultas,
            storeUrl: config.storeUrl,
            updateUrlBase: config.updateUrlBase,
            open: false,
            mode: 'create',
            form: emptyForm(),

            init() {
                if (config.autoOpen) {
                    this.form.nim = config.oldNim || '';
                    this.form.nama = config.oldNama || '';
                    this.form.fakultas_id = config.oldFakultasId || '';
                    this.form.unit_id = config.oldUnitId || '';
                    this.form.tahun_akademik = config.oldTahunAkademik || config.defaultTahunAkademik || '';
                    this.form.status = config.oldStatus || 'aktif';
                    this.mode = 'create';
                    this.open = true;
                }
            },

            get prodiList() {
                if (!this.form.fakultas_id) return [];
                const fak = this.fakultas.find(f => String(f.id) === String(this.form.fakultas_id));
                return fak ? fak.prodi : [];
            },

            get formAction() {
                return this.mode === 'edit'
                    ? `${this.updateUrlBase}/${encodeURIComponent(this.form.nim)}`
                    : this.storeUrl;
            },

            openCreate() {
                this.mode = 'create';
                this.form = emptyForm();
                this.open = true;
            },

            openEdit(data) {
                this.mode = 'edit';
                this.form.nim = String(data.nim ?? '');
                this.form.nama = data.nama ?? '';
                this.form.fakultas_id = data.fakultas_id != null ? String(data.fakultas_id) : '';
                this.form.tahun_akademik = data.tahun_akademik ?? (config.defaultTahunAkademik || '');
                this.form.status = data.status ?? 'aktif';
                this.form.unit_id = '';
                this.open = true;
                const targetUnitId = data.unit_id != null ? String(data.unit_id) : '';
                this.$nextTick(() => { this.form.unit_id = targetUnitId; });
            },

            close() {
                this.open = false;
            },
        };
    }
</script>
@endpush
@endsection
