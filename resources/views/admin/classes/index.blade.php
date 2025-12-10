@extends('layouts.admin')

@section('title', 'Manajemen Kelas - Admin Flexora')
@section('page-title', 'Manajemen Kelas')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-semibold text-gray-800">Daftar Kelas Studio</h3>
    <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Kelas Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Jadwal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($classes as $class)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($class->description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $class->slug }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $class->schedules_count }} jadwal</td>
                    <td class="px-6 py-4">
                        @if($class->image)
                            <img src="/storage/{{ $class->image }}" alt="{{ $class->name }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-400 text-xs">No Image</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <a href="{{ route('admin.classes.edit', $class) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada kelas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6 flex justify-center">
    <div class="bg-white px-4 py-3 rounded-lg shadow">
        {{ $classes->links() }}
    </div>
</div>
@endsection
