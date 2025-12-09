@extends('layouts.admin')

@section('title', 'Manajemen Membership - Admin Flexora')
@section('page-title', 'Manajemen Membership')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-xl font-semibold text-gray-800">Daftar Paket Membership</h3>
    <a href="{{ route('admin.memberships.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Tambah Membership Baru
    </a>
</div>

<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total User</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($memberships as $membership)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $membership->name }}</div>
                        @if($membership->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($membership->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        Rp {{ number_format($membership->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $membership->duration_days }} hari</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $membership->users_count }} user</td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                        <a href="{{ route('admin.memberships.edit', $membership) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.memberships.destroy', $membership) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus membership ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada paket membership</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $memberships->links() }}
</div>
@endsection
