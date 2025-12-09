@extends('layouts.admin')

@section('title', 'Tambah Membership - Admin Flexora')
@section('page-title', 'Tambah Membership Baru')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.memberships.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Paket <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g., Paket 1 Bulan"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga (Rupiah) <span class="text-red-500">*</span></label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0" placeholder="100000"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('price') border-red-500 @enderror">
            @error('price')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-2">Durasi (Hari) <span class="text-red-500">*</span></label>
            <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days') }}" required min="1" placeholder="30"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('duration_days') border-red-500 @enderror">
            <p class="mt-1 text-xs text-gray-500">Contoh: 30 untuk 1 bulan, 90 untuk 3 bulan, 365 untuk 1 tahun</p>
            @error('duration_days')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (opsional)</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.memberships.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition">
                Simpan Membership
            </button>
        </div>
    </form>
</div>
@endsection
