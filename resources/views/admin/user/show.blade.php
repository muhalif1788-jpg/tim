@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                    <span class="font-bold text-red-600 text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                    <div class="flex items-center space-x-3 mt-1">
                        <span class="text-red-100 text-sm">{{ $user->email }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.user.index') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <a href="{{ route('admin.user.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Grid Informasi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Utama -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                    Informasi Profil
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                        <div class="text-gray-900 font-medium">{{ $user->name }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <div class="text-gray-900">{{ $user->email }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Telepon</label>
                        <div class="text-gray-900">{{ $user->phone ?? '-' }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role</label>
                        <div class="text-gray-900">{{ $user->role }}</div>
                    </div>
                </div>
            </div>

            <!-- Alamat dan Info Tambahan -->
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-200">
                    Alamat
                </h3>
                
                <div>
                    @if($user->address)
                        <div class="text-gray-900">{{ $user->address }}</div>
                    @else
                        <div class="text-gray-400 italic">Belum ada alamat</div>
                    @endif
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-4 pb-3 border-b border-gray-200">
                    Informasi Akun
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Bergabung Sejak</label>
                        <div class="text-gray-900">{{ $user->created_at->format('d F Y') }}</div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">ID User</label>
                        <div class="text-gray-900">{{ $user->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection