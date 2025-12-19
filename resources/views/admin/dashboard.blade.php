@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Produk -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-box text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Produk</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalProduk ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total Kategori -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-list text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Kategori</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalKategori ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Transaksi</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalTransaksi ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Total User -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-lg">
                <i class="fas fa-users text-red-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total User</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUser ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.produk.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg text-center transition-colors">
            <i class="fas fa-plus text-xl mb-2 block"></i>
            <span class="font-medium">Tambah Produk</span>
        </a>
        <a href="{{ route('admin.kategori.create') }}" class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg text-center transition-colors">
            <i class="fas fa-tags text-xl mb-2 block"></i>
            <span class="font-medium">Tambah Kategori</span>
        </a>
        <a href="{{ route('admin.transactions.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg text-center transition-colors">
            <i class="fas fa-shopping-cart text-xl mb-2 block"></i>
            <span class="font-medium">Lihat Transaksi</span>
        </a>
        <a href="{{ route('admin.user.index') }}" class="bg-red-500 hover:bg-red-600 text-white p-4 rounded-lg text-center transition-colors">
            <i class="fas fa-users text-xl mb-2 block"></i>
            <span class="font-medium">Kelola User</span>
        </a>
    </div>
</div>
@endsection