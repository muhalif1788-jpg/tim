@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('page-title', 'Detail Produk')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-white">Detail Produk</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.produk.edit', $produk) }}" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                        <i class="fas fa-edit"></i>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('admin.produk.index') }}" class="bg-transparent border border-white text-white hover:bg-white/10 px-4 py-2 rounded-lg font-medium transition-colors">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Gambar Produk -->
                <div class="md:col-span-1">
                    <div class="bg-gray-50 rounded-lg border border-gray-200 p-6 text-center">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" 
                                 class="w-full h-64 object-cover rounded-lg mb-4">
                        @else
                            <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                                <i class="fas fa-image text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="mt-4">
                            {!! $produk->status_badge !!}
                        </div>
                    </div>
                </div>

                <!-- Informasi Detail -->
                <div class="md:col-span-2">
                    <div class="space-y-6">
                        <!-- Informasi Produk -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Produk</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nama Produk</label>
                                    <p class="mt-1 text-gray-900 font-medium">{{ $produk->nama_produk }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kategori</label>
                                    <p class="mt-1">
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            {{ $produk->kategori->nama_kategori ?? '-' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Harga</label>
                                    <p class="mt-1 text-gray-900 font-bold text-lg">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Stok</label>
                                    <p class="mt-1 text-gray-900">
                                        <span class="{{ $produk->stok > 0 ? 'text-green-600' : 'text-red-600' }} font-bold text-lg">
                                            {{ $produk->stok }}
                                        </span>
                                        <span class="text-gray-500 text-sm ml-1">{{ $produk->satuan }}</span>
                                    </p>
                                    @if($produk->stok <= 5 && $produk->stok > 0)
                                        <p class="text-xs text-yellow-600 mt-1">⚠️ Stok menipis!</p>
                                    @elseif($produk->stok == 0)
                                        <p class="text-xs text-red-600 mt-1">⚠️ Stok habis!</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detail Teknis -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Detail Teknis</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Berat</label>
                                    <p class="mt-1 text-gray-900">{{ $produk->berat }} gram</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Satuan</label>
                                    <p class="mt-1 text-gray-900">{{ $produk->satuan }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <p class="mt-1">{!! $produk->status_badge !!}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kode Produk</label>
                                    <p class="mt-1 text-gray-900">#{{ str_pad($produk->id, 6, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Deskripsi</h4>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                @if($produk->deskripsi)
                                    <p class="text-gray-700">{{ $produk->deskripsi }}</p>
                                @else
                                    <p class="text-gray-500 italic">Tidak ada deskripsi</p>
                                @endif
                            </div>
                        </div>

                        <!-- Informasi Tanggal -->
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Tanggal</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Ditambahkan Pada</label>
                                    <p class="mt-1 text-gray-900">{{ $produk->created_at->format('d F Y H:i') }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Terakhir Update</label>
                                    <p class="mt-1 text-gray-900">{{ $produk->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection