@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('content')
<!-- Breadcrumb -->
<nav class="mb-6 flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400">
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Data Mahasiswa
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">Tambah Mahasiswa</span>
            </div>
        </li>
    </ol>
</nav>

<!-- Form Card -->
<div class="overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow transition-colors duration-200">
    <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-4 transition-colors duration-200">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Tambah Mahasiswa Baru</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Masukkan data mahasiswa baru ke dalam sistem</p>
    </div>

    <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="px-6 py-6">
        @csrf

        <div class="space-y-6">
            <!-- NIM Field -->
            <div>
                <label for="nim" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    NIM <span class="text-red-500 dark:text-red-400">*</span>
                </label>
                <input type="text" 
                       name="nim" 
                       id="nim" 
                       value="{{ old('nim') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nim') border-red-300 dark:border-red-600 @enderror"
                       placeholder="Contoh: 2021001"
                       required>
                @error('nim')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">NIM harus unik dan tidak boleh sama dengan mahasiswa lain</p>
            </div>

            <!-- Nama Field -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Nama Lengkap <span class="text-red-500 dark:text-red-400">*</span>
                </label>
                <input type="text" 
                       name="nama" 
                       id="nama" 
                       value="{{ old('nama') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nama') border-red-300 dark:border-red-600 @enderror"
                       placeholder="Contoh: Budi Santoso"
                       required>
                @error('nama')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fakultas Field -->
            <div>
                <label for="fakultas_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Fakultas <span class="text-red-500 dark:text-red-400">*</span>
                </label>
                <select
                    name="fakultas_id"
                    id="fakultas_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('unit_id') border-red-300 dark:border-red-600 @enderror"
                    required
                >
                    <option value="">Pilih Fakultas</option>
                    @foreach ($fakultas as $item)
                        <option value="{{ $item->id }}" {{ old('fakultas_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->nama }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Pilih fakultas terlebih dahulu untuk memunculkan daftar program studi.</p>
            </div>

            <!-- Program Studi Field -->
            <div>
                <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Program Studi <span class="text-red-500 dark:text-red-400">*</span>
                </label>
                <select
                    name="unit_id"
                    id="unit_id"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('unit_id') border-red-300 dark:border-red-600 @enderror"
                    required
                >
                    <option value="">Pilih Program Studi</option>
                    @foreach ($fakultas as $item)
                        @foreach ($item->children as $prodi)
                            <option
                                value="{{ $prodi->id }}"
                                data-fakultas="{{ $item->id }}"
                                {{ old('unit_id') == $prodi->id ? 'selected' : '' }}
                            >
                                {{ $prodi->nama }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
                @error('unit_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="rounded-md bg-blue-50 dark:bg-blue-900/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-400">Informasi</h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>Data mahasiswa yang ditambahkan akan menjadi Single Source of Truth dan dapat diakses oleh sistem lain melalui API.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex items-center justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-6">
            <a href="{{ route('admin.mahasiswa.index') }}" 
               class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Batal
            </a>
            <button type="submit" 
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fakultasSelect = document.getElementById('fakultas_id');
        const prodiSelect = document.getElementById('unit_id');
        const prodiOptions = Array.from(prodiSelect.querySelectorAll('option[data-fakultas]'));

        function filterProdi() {
            const selectedFakultas = fakultasSelect.value;
            const currentProdi = prodiSelect.value;

            prodiOptions.forEach((option) => {
                const isVisible = !selectedFakultas || option.dataset.fakultas === selectedFakultas;
                option.hidden = !isVisible;
            });

            if (currentProdi) {
                const selectedOption = prodiSelect.querySelector(`option[value="${currentProdi}"]`);
                if (selectedOption && selectedOption.hidden) {
                    prodiSelect.value = '';
                }
            }
        }

        filterProdi();
        fakultasSelect.addEventListener('change', filterProdi);
    });
</script>
@endpush
