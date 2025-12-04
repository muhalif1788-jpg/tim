@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Daftar Produk</h2>
            <a href="{{ route('admin.produk.create') }}" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Produk</span>
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

        <!-- Filter dan Search -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('admin.produk.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           placeholder="Nama produk atau deskripsi">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('admin.produk.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tabel Produk -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Gambar</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Produk</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Harga</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stok</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produks as $produk)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Gambar -->
                        <td class="px-4 py-3">
                            @if($produk->gambar)
                                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" 
                                     class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        
                        <!-- Nama Produk -->
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $produk->nama_produk }}</div>
                            @if($produk->deskripsi)
                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($produk->deskripsi, 50) }}</div>
                            @endif
                            <div class="text-xs text-gray-400 mt-1">
                                Berat: {{ $produk->berat }}g • Satuan: {{ $produk->satuan }}
                            </div>
                        </td>
                        
                        <!-- Kategori -->
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                {{ $produk->kategori->nama_kategori ?? '-' }}
                            </span>
                        </td>
                        
                        <!-- Harga -->
                        <td class="px-4 py-3 font-medium text-gray-900">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </td>
                        
                        <!-- Stok -->
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <span class="{{ $produk->stok > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                    {{ $produk->stok }}
                                </span>
                            </div>
                            @if($produk->stok <= 5 && $produk->stok > 0)
                                <div class="text-xs text-yellow-600">Stok menipis!</div>
                            @elseif($produk->stok == 0)
                                <div class="text-xs text-red-600">Habis</div>
                            @endif
                        </td>
                        
                        <!-- Status -->
                        <td class="px-4 py-3">
                            {!! $produk->status_badge !!}
                        </td>
                        
                        <!-- Aksi -->
                        <td class="px-4 py-3">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.produk.show', $produk) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded transition-colors" title="Detail">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.produk.edit', $produk) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition-colors" title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition-colors" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-box text-3xl mb-2 block text-gray-300"></i>
                            Tidak ada produk ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($produks->hasPages())
            <div class="mt-6">
                {{ $produks->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection