@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Daftar User</h2>
            <a href="{{ route('admin.user.create') }}" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-user-plus"></i>
                <span>Tambah User</span>
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    {{ session('error') }}
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filter dan Search -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('admin.user.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           placeholder="Nama, Email, atau Telepon">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.user.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel User -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Telepon</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Bergabung</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Nama -->
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <span class="font-semibold text-red-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->address)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($user->address, 30) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        
                        <!-- Email -->
                        <td class="px-4 py-3">
                            <div class="text-gray-700">{{ $user->email }}</div>
                            @if($user->email_verified_at)
                                <div class="text-xs text-green-600 flex items-center mt-1">
                                    <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                </div>
                            @else
                                <div class="text-xs text-yellow-600 flex items-center mt-1">
                                    <i class="fas fa-exclamation-circle mr-1"></i> Belum Verifikasi
                                </div>
                            @endif
                        </td>
                        
                        <!-- Role -->
                        <td class="px-4 py-3">
                            {!! $user->role_badge !!}
                        </td>
                        
                        <!-- Telepon -->
                        <td class="px-4 py-3 text-gray-700">
                            {{ $user->phone ?? '-' }}
                        </td>
                        
                        <!-- Bergabung -->
                        <td class="px-4 py-3">
                            <div class="text-sm text-gray-600">
                                {{ $user->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </td>
                        
                        <!-- Aksi -->
                        <td class="px-4 py-3">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.user.show', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded transition-colors" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.user.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.user.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition-colors" title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="bg-gray-300 text-gray-500 p-2 rounded cursor-not-allowed" title="Tidak dapat menghapus akun sendiri" disabled>
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-3xl mb-2 block text-gray-300"></i>
                            Tidak ada user ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection