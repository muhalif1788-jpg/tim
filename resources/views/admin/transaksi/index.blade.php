@extends('layouts.admin')

@section('title', 'Daftar Transaksi')
@section('page-title', 'Manajemen Transaksi')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Daftar Transaksi</h2>
            <button id="btnTambah" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Transaksi Baru</span>
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

        <!-- Form Tambah Transaksi -->
        <div id="formContainer" class="bg-gray-50 rounded-lg border border-gray-200 p-6 mb-6" style="display:none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Baru</h3>
            
            <form id="transaksiForm" action="{{ route('admin.transaksi.store') }}" method="POST">
                @csrf

                <!-- Informasi Pembeli -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pembeli</label>
                        <input type="text" name="nama_pembeli" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama pembeli" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                    </div>
                </div>

                <!-- Daftar Produk -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-shopping-cart mr-2 text-red-500"></i>
                        Produk yang Dibeli
                    </h4>

                    <div id="produkContainer" class="space-y-3">
                        <div class="produkItem bg-white p-4 rounded-lg border border-gray-200">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                <div class="col-span-5">
                                    <select name="produk_id[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 produkSelect" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach($produks as $produk)
                                            <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}">
                                                {{ $produk->nama }} - Rp{{ number_format($produk->harga, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" name="jumlah[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 jumlah" placeholder="Jumlah" min="1" required>
                                </div>
                                <div class="col-span-3">
                                    <input type="text" name="subtotal[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 subtotal" placeholder="Subtotal" readonly>
                                </div>
                                <div class="col-span-1 text-center">
                                    <button type="button" class="btnHapusProduk text-red-500 hover:text-red-700 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="btnTambahProduk" class="mt-4 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Produk Lain</span>
                    </button>
                </div>

                <!-- Total dan Tombol -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-800">Total Transaksi</h4>
                        <div class="text-2xl font-bold text-red-600">
                            Rp<span id="totalHarga">0</span>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center space-x-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Transaksi</span>
                        </button>
                        <button type="button" id="btnBatal" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID Transaksi</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Pembeli</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Total Harga</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($transaksi as $t)  <!-- ✅ PERBAIKI: $transaksi bukan $transaksis -->
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-mono text-sm text-gray-600">#{{ $t->id_transaksi }}</span> <!-- ✅ id_transaksi -->
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900">{{ $t->nama_pembeli }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</span> <!-- ✅ tanggal -->
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-semibold text-green-600">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.transaksi.show', $t->id_transaksi) }}" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded transition-colors" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.transaksi.destroy', $t->id_transaksi) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
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
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-receipt text-3xl mb-2 block text-gray-300"></i>
                                Belum ada transaksi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formContainer = document.getElementById('formContainer');
    const btnTambah = document.getElementById('btnTambah');
    const btnBatal = document.getElementById('btnBatal');
    const btnTambahProduk = document.getElementById('btnTambahProduk');
    const produkContainer = document.getElementById('produkContainer');
    const totalHargaEl = document.getElementById('totalHarga');

    function hitungTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseFloat(el.value || 0);
        });
        totalHargaEl.textContent = total.toLocaleString('id-ID');
    }

    function updateSubtotal(row) {
        const select = row.querySelector('.produkSelect');
        const jumlah = row.querySelector('.jumlah').value;
        const harga = select.selectedOptions[0]?.dataset.harga || 0;
        const subtotal = jumlah * harga;
        row.querySelector('.subtotal').value = subtotal.toLocaleString('id-ID');
        hitungTotal();
    }

    // Toggle form
    btnTambah.addEventListener('click', () => {
        formContainer.style.display = 'block';
        btnTambah.style.display = 'none';
    });

    btnBatal.addEventListener('click', () => {
        formContainer.style.display = 'none';
        btnTambah.style.display = 'block';
        // Reset form
        document.getElementById('transaksiForm').reset();
        hitungTotal();
    });

    // Tambah produk
    btnTambahProduk.addEventListener('click', () => {
        const newItem = produkContainer.firstElementChild.cloneNode(true);
        newItem.querySelectorAll('input').forEach(input => input.value = '');
        newItem.querySelector('select').selectedIndex = 0;
        produkContainer.appendChild(newItem);
    });

    // Event delegation untuk input dan hapus produk
    produkContainer.addEventListener('input', (e) => {
        if (e.target.classList.contains('produkSelect') || e.target.classList.contains('jumlah')) {
            const row = e.target.closest('.produkItem');
            updateSubtotal(row);
        }
    });

    produkContainer.addEventListener('click', (e) => {
        if (e.target.closest('.btnHapusProduk')) {
            const row = e.target.closest('.produkItem');
            if (produkContainer.children.length > 1) {
                row.remove();
                hitungTotal();
            }
        }
    });

    // Inisialisasi
    hitungTotal();
});
</script>
@endsection