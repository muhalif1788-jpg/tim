@extends('layouts.admin')

@section('title', 'Daftar User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-white">Daftar User</h2>
            <button id="btnTambah" class="bg-white text-red-600 hover:bg-gray-100 px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Tambah User</span>
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

        <!-- Form Tambah User (hidden by default) -->
        <div id="formTambah" class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200" style="display: none;">
            <form action="{{ route('admin.user.store') }}" method="POST"> <!-- ✅ PERBAIKI: admin.user.store -->
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Nama Lengkap" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Email" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
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

        <!-- Tabel User -->
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Nama</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Role</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 text-gray-700">{{ $user->id }}</td>

                        <!-- Kolom Nama -->
                        <td class="px-4 py-3">
                            <span class="nama-text">{{ $user->name }}</span>
                            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="form-inline-edit hidden"> <!-- ✅ PERBAIKI: admin.user.update -->
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $user->name }}" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500" required>
                            </form>
                        </td>

                        <!-- Kolom Email -->
                        <td class="px-4 py-3">
                            <span class="email-text">{{ $user->email }}</span>
                            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="form-inline-edit-email hidden"> <!-- ✅ PERBAIKI: admin.user.update -->
                                @csrf
                                @method('PUT')
                                <input type="email" name="email" value="{{ $user->email }}" 
                                       class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500" required>
                            </form>
                        </td>

                        <!-- Kolom Role -->
                        <td class="px-4 py-3">
                            <span class="role-text">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </span>
                            <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="form-inline-edit-role hidden"> <!-- ✅ PERBAIKI: admin.user.update -->
                                @csrf
                                @method('PUT')
                                <select name="role" class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-red-500">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                </select>
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
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')"> <!-- ✅ PERBAIKI: admin.user.destroy -->
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
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-3xl mb-2 block text-gray-300"></i>
                            Belum ada user.
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

    // Edit user (inline)
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Hide text, show forms
            tr.querySelectorAll('.nama-text, .email-text, .role-text').forEach(el => el.classList.add('hidden'));
            tr.querySelectorAll('.form-inline-edit, .form-inline-edit-email, .form-inline-edit-role').forEach(el => el.classList.remove('hidden'));
            
            // Switch buttons
            tr.querySelector('.action-view').classList.add('hidden');
            tr.querySelector('.action-edit').classList.remove('hidden');
        });
    });

    // Simpan edit
    document.querySelectorAll('.btn-simpan-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const tr = this.closest('tr');
            
            // Submit form yang sedang aktif
            const activeForms = tr.querySelectorAll('.form-inline-edit:not(.hidden), .form-inline-edit-email:not(.hidden), .form-inline-edit-role:not(.hidden)');
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
            tr.querySelectorAll('.nama-text, .email-text, .role-text').forEach(el => el.classList.remove('hidden'));
            tr.querySelectorAll('.form-inline-edit, .form-inline-edit-email, .form-inline-edit-role').forEach(el => el.classList.add('hidden'));
            
            // Switch buttons
            tr.querySelector('.action-view').classList.remove('hidden');
            tr.querySelector('.action-edit').classList.add('hidden');
        });
    });
});
</script>
@endsection