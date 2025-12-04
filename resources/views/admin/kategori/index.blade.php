@extends('layouts.admin')

@section('title', 'Daftar Kategori')
@section('page-title', 'Manajemen Kategori')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Daftar Kategori</h2>
            <button id="btnTambah" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah Kategori</span>
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

        <!-- Form Tambah Kategori -->
        <div id="formTambah" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200" style="display: none;">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Masukkan nama kategori" required>
                    </div>
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
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

        <!-- Tabel Kategori -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama Kategori</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoris as $kategori)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-700">{{ $kategori->id }}</td>
                        
                        <!-- Kolom Nama -->
                        <td class="px-4 py-3">
                            <span class="nama-text font-medium text-gray-900">{{ $kategori->nama_kategori }}</span>
                            <form action="{{ route('admin.kategori.update', $kategori) }}" method="POST" class="form-inline-edit hidden">
                                @csrf
                                @method('PUT')
                                <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500" required>
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
                                    <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-tags text-3xl mb-2 block text-gray-300"></i>
                            Belum ada kategori.
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

    // Edit kategori (inline)
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Hide text, show form
            tr.querySelector('.nama-text').classList.add('hidden');
            tr.querySelector('.form-inline-edit').classList.remove('hidden');
            
            // Switch buttons
            tr.querySelector('.action-view').classList.add('hidden');
            tr.querySelector('.action-edit').classList.remove('hidden');
        });
    });

    // Simpan edit
    document.querySelectorAll('.btn-simpan-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            const form = tr.querySelector('.form-inline-edit');
            form.submit();
        });
    });

    // Batal edit
    document.querySelectorAll('.btn-batal-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Show text, hide form
            tr.querySelector('.nama-text').classList.remove('hidden');
            tr.querySelector('.form-inline-edit').classList.add('hidden');
            
            // Switch buttons
            tr.querySelector('.action-view').classList.remove('hidden');
            tr.querySelector('.action-edit').classList.add('hidden');
        });
    });
});
</script>
@endsection