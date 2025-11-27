@extends('layouts.admin')

@section('title', 'Daftar Produk')
@section('page-title', 'Manajemen Produk')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Daftar Produk</h2>
            <button id="btnTambah" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Produk</span>
            </button>
        </div>
    </div>

    <div class="p-6">
        <!-- Notifikasi sukses -->
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

        <!-- Form Tambah Produk -->
        <div id="formTambah" class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-6" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tambah Produk Baru</h3>
            <form action="{{ route('admin.produk.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                        <input type="text" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Nama produk" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <input type="number" name="harga" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Harga" min="0" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                        <input type="number" name="stok" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Stok" min="0" required>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2 flex-1">
                            <i class="fas fa-save"></i>
                            <span>Simpan</span>
                        </button>
                        <button type="button" id="btnBatal" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Produk -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Produk</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Kategori</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Harga</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stok</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produks as $produk)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-mono text-sm text-gray-600">#{{ $produk->id }}</span>
                        </td>
                        
                        <!-- Kolom Nama -->
                        <td class="px-4 py-3">
                            <span class="nama-text font-medium text-gray-900">{{ $produk->nama }}</span>
                            <form action="{{ route('admin.produk.update', $produk) }}" method="POST" class="form-inline-edit hidden">
                                @csrf
                                @method('PUT')
                                <input type="text" name="nama" value="{{ $produk->nama }}" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500" required>
                            </form>
                        </td>
                        
                        <!-- Kolom Kategori -->
                        <td class="px-4 py-3">
                            <span class="kategori-text">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $produk->kategori->nama ?? '-' }}
                                </span>
                            </span>
                            <form action="{{ route('admin.produk.update', $produk) }}" method="POST" class="form-inline-edit-kategori hidden">
                                @csrf
                                @method('PUT')
                                <select name="kategori_id" class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500">
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ $produk->kategori_id == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        
                        <!-- Kolom Harga -->
                        <td class="px-4 py-3">
                            <span class="harga-text font-semibold text-green-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            <form action="{{ route('admin.produk.update', $produk) }}" method="POST" class="form-inline-edit-harga hidden">
                                @csrf
                                @method('PUT')
                                <input type="number" name="harga" value="{{ $produk->harga }}" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500" min="0" required>
                            </form>
                        </td>
                        
                        <!-- Kolom Stok -->
                        <td class="px-4 py-3">
                            <span class="stok-text">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $produk->stok > 10 ? 'bg-green-100 text-green-800' : 
                                       ($produk->stok > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $produk->stok }} pcs
                                </span>
                            </span>
                            <form action="{{ route('admin.produk.update', $produk) }}" method="POST" class="form-inline-edit-stok hidden">
                                @csrf
                                @method('PUT')
                                <input type="number" name="stok" value="{{ $produk->stok }}" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500" min="0" required>
                            </form>
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="px-4 py-3">
                            <div class="flex justify-center space-x-2">
                                <!-- Tombol Edit/Hapus -->
                                <div class="action-view">
                                    <button class="btn-edit bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded transition-colors">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.produk.destroy', $produk) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded transition-colors ml-1">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Tombol Simpan/Batal saat edit -->
                                <div class="action-edit hidden space-x-1">
                                    <button type="button" class="btn-simpan-edit bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Simpan
                                    </button>
                                    <button type="button" class="btn-batal-edit bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-box text-3xl mb-2 block text-gray-300"></i>
                            Belum ada produk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnTambah = document.getElementById('btnTambah');
    const formTambah = document.getElementById('formTambah');
    const btnBatal = document.getElementById('btnBatal');

    // Toggle form tambah
    btnTambah.addEventListener('click', () => {
        formTambah.style.display = 'block';
        btnTambah.style.display = 'none';
    });

    btnBatal.addEventListener('click', () => {
        formTambah.style.display = 'none';
        btnTambah.style.display = 'block';
    });

    // Edit produk (inline)
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Hide text, show forms
            tr.querySelectorAll('.nama-text, .kategori-text, .harga-text, .stok-text').forEach(el => el.classList.add('hidden'));
            tr.querySelectorAll('.form-inline-edit, .form-inline-edit-kategori, .form-inline-edit-harga, .form-inline-edit-stok').forEach(el => el.classList.remove('hidden'));
            
            // Switch buttons
            tr.querySelector('.action-view').classList.add('hidden');
            tr.querySelector('.action-edit').classList.remove('hidden');
        });
    });

    // Simpan edit
    document.querySelectorAll('.btn-simpan-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Submit semua form yang sedang aktif
            const activeForms = tr.querySelectorAll('.form-inline-edit:not(.hidden), .form-inline-edit-kategori:not(.hidden), .form-inline-edit-harga:not(.hidden), .form-inline-edit-stok:not(.hidden)');
            activeForms.forEach(form => {
                form.submit();
            });
        });
    });

    // Batal edit
    document.querySelectorAll('.btn-batal-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Show text, hide forms
            tr.querySelectorAll('.nama-text, .kategori-text, .harga-text, .stok-text').forEach(el => el.classList.remove('hidden'));
            tr.querySelectorAll('.form-inline-edit, .form-inline-edit-kategori, .form-inline-edit-harga, .form-inline-edit-stok').forEach(el => el.classList.add('hidden'));
            
            // Switch buttons
            tr.querySelector('.action-view').classList.remove('hidden');
            tr.querySelector('.action-edit').classList.add('hidden');
        });
    });
});
</script>
@endsection