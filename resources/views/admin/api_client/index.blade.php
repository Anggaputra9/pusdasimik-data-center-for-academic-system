@extends('layouts.app')

@section('title', 'Manajemen Klien API')

@section('content')
<div
    x-data="apiClientPage({
        storeUrl: '{{ route('admin.api-client.store') }}',
        updateUrlBase: '{{ url('admin/api-client') }}',
        autoOpen: {{ $errors->any() ? 'true' : 'false' }},
        oldNama: '{{ old('nama') }}',
        oldSlug: '{{ old('slug') }}',
        oldDeskripsi: '{{ old('deskripsi') }}',
    })"
    x-init="init()"
    class="space-y-6"
>
    @if($plainToken)
        <div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-5 shadow-sm dark:border-emerald-700 dark:bg-emerald-900/30">
            <div class="flex items-start gap-3">
                <svg class="h-6 w-6 flex-shrink-0 text-emerald-600 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-emerald-900 dark:text-emerald-200">Token baru untuk {{ $plainTokenClient }}</h3>
                    <p class="mt-1 text-sm text-emerald-800 dark:text-emerald-300">Salin sekarang — token plaintext hanya muncul satu kali.</p>
                    <div class="mt-3 flex items-center gap-2">
                        <code class="block flex-1 break-all rounded-lg border border-emerald-200 bg-white px-3 py-2 font-mono text-sm text-gray-900 dark:border-emerald-700 dark:bg-gray-900 dark:text-gray-100">{{ $plainToken }}</code>
                        <button type="button"
                                onclick="navigator.clipboard.writeText('{{ $plainToken }}'); this.innerText='Tersalin';"
                                class="rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                            Salin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Card -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-800">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Klien API</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Daftar aplikasi klien yang berhak mengakses API Pusat Data.</p>
            </div>
            <button type="button" @click="openCreate()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Klien
            </button>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($clients as $client)
                @php
                    $editPayload = [
                        'id' => $client->id,
                        'nama' => $client->nama,
                        'deskripsi' => $client->deskripsi,
                        'is_active' => $client->is_active,
                    ];
                @endphp
                <div class="px-6 py-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $client->nama }}</h4>
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $client->is_active
                                        ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300'
                                        : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $client->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <code class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ $client->slug }}</code>
                            </div>
                            @if($client->deskripsi)
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $client->deskripsi }}</p>
                            @endif

                            <div class="mt-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Token Aktif ({{ $client->tokens->count() }})</p>
                                    <form action="{{ route('admin.api-client.tokens.store', $client) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="text" name="token_name" required maxlength="50" placeholder="nama token (mis. prod-2026)"
                                               class="rounded-lg border border-gray-300 bg-white px-2 py-1 text-xs text-gray-900 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1 text-xs font-medium text-white hover:bg-emerald-700">
                                            Terbitkan Token
                                        </button>
                                    </form>
                                </div>

                                <div class="mt-2 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Last Used</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Dibuat</th>
                                                <th class="px-3 py-2 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                            @forelse($client->tokens as $token)
                                                <tr>
                                                    <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $token->name }}</td>
                                                    <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500 dark:text-gray-400">{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'belum dipakai' }}</td>
                                                    <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500 dark:text-gray-400">{{ $token->created_at->format('d M Y H:i') }}</td>
                                                    <td class="whitespace-nowrap px-3 py-2 text-right text-sm">
                                                        <form action="{{ route('admin.api-client.tokens.destroy', [$client, $token->id]) }}" method="POST"
                                                              onsubmit="return confirm('Cabut token {{ $token->name }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400">Cabut</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="px-3 py-3 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada token.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-shrink-0 items-center gap-2">
                            <button type="button" @click="openEdit({{ Js::from($editPayload) }})"
                                    class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                                Edit
                            </button>
                            <form action="{{ route('admin.api-client.destroy', $client) }}" method="POST"
                                  onsubmit="return confirm('Hapus klien {{ $client->nama }} beserta semua tokennya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-300 bg-white px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-50 dark:border-red-700 dark:bg-gray-700 dark:text-red-300 dark:hover:bg-gray-600">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada klien API.</div>
            @endforelse
        </div>
    </div>

    <!-- Modal create/edit -->
    <div x-show="open" x-cloak @keydown.escape.window="close()"
         class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <div x-show="open" x-transition.opacity @click="close()"
                 class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-900/75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition
                 class="relative inline-block w-full transform overflow-hidden rounded-2xl bg-white text-left align-bottom shadow-xl transition-all dark:bg-gray-800 sm:my-8 sm:max-w-lg sm:align-middle">
                <form :action="formAction" method="POST">
                    @csrf
                    <template x-if="mode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex items-start justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="mode === 'edit' ? 'Edit Klien API' : 'Tambah Klien API Baru'"></h3>
                        <button type="button" @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="nama" required maxlength="100"
                                   x-model="form.nama"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div x-show="mode === 'create'">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug (opsional)</label>
                            <input type="text" name="slug" maxlength="50"
                                   x-model="form.slug"
                                   placeholder="otomatis dari nama jika dikosongkan"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                            <textarea name="deskripsi" rows="3" maxlength="255"
                                      x-model="form.deskripsi"
                                      class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                        </div>

                        <div x-show="mode === 'edit'" class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" id="is_active_chk"
                                   x-model="form.is_active"
                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active_chk" class="text-sm text-gray-700 dark:text-gray-300">Klien aktif (boleh mengakses API)</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-3 dark:border-gray-700 dark:bg-gray-700/40">
                        <button type="button" @click="close()"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">Batal</button>
                        <button type="submit"
                                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
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
    function apiClientPage(config) {
        const emptyForm = () => ({ nama: '', slug: '', deskripsi: '', is_active: true });
        return {
            storeUrl: config.storeUrl,
            updateUrlBase: config.updateUrlBase,
            open: false,
            mode: 'create',
            editingId: null,
            form: emptyForm(),

            init() {
                if (config.autoOpen) {
                    this.form.nama = config.oldNama || '';
                    this.form.slug = config.oldSlug || '';
                    this.form.deskripsi = config.oldDeskripsi || '';
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
                this.form = emptyForm();
                this.open = true;
            },

            openEdit(data) {
                this.mode = 'edit';
                this.editingId = data.id;
                this.form = {
                    nama: data.nama ?? '',
                    slug: '',
                    deskripsi: data.deskripsi ?? '',
                    is_active: !!data.is_active,
                };
                this.open = true;
            },

            close() { this.open = false; },
        };
    }
</script>
@endpush
@endsection
