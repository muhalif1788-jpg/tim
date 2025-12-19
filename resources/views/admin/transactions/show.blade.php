@extends('layouts.admin')

@section('page-title', 'Detail Transaksi #' . $transaction->order_id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.transactions.index') }}" class="inline-flex items-center text-red-500 hover:text-red-600">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informasi Utama -->
    <div class="lg:col-span-2">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Order #{{ $transaction->order_id }}</h2>
                    <p class="text-gray-600 mt-1">
                        <i class="far fa-calendar mr-1"></i> {{ $transaction->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                
                <div class="mt-4 md:mt-0">
                    @if($transaction->status == 'success')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i> Sukses
                        </span>
                    @elseif($transaction->status == 'pending')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i> Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i> Gagal
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Informasi Customer -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-gray-700 mb-3">
                        <i class="fas fa-user text-red-500 mr-2"></i> Informasi Customer
                    </h4>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Nama:</span>
                            <span class="font-medium">{{ $transaction->nama_penerima }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Email:</span>
                            <span>{{ $transaction->user->email ?? 'Guest' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Telepon:</span>
                            <span>{{ $transaction->telepon_penerima }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Alamat:</span>
                            <span>{{ $transaction->alamat_pengiriman }}</span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-gray-700 mb-3">
                        <i class="fas fa-receipt text-red-500 mr-2"></i> Informasi Pembayaran
                    </h4>
                    <div class="space-y-2">
                        <div class="flex">
                            <span class="text-gray-600 w-32">Metode:</span>
                            <span>{{ $transaction->payment_type ?? 'Midtrans' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">ID Transaksi:</span>
                            <span class="font-mono">{{ $transaction->transaction_id ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Snap Token:</span>
                            <span class="font-mono text-xs truncate">{{ $transaction->snap_token ?? '-' }}</span>
                        </div>
                        <div class="flex">
                            <span class="text-gray-600 w-32">Expired:</span>
                            <span>{{ $transaction->expired_at ? $transaction->expired_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detail Items -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h4 class="font-bold text-gray-700 mb-4">
                <i class="fas fa-boxes text-red-500 mr-2"></i> Detail Produk
            </h4>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Produk</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Harga</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Qty</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($transaction->details as $detail)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $detail->produk->nama ?? 'Produk tidak ditemukan' }}</div>
                                @if($detail->produk)
                                <div class="text-sm text-gray-500">ID: {{ $detail->produk_id }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">Rp {{ number_format($detail->harga_saat_ini, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $detail->jumlah }}</td>
                            <td class="px-4 py-3 font-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Kanan -->
    <div class="space-y-6">
        <!-- Ringkasan Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h4 class="font-bold text-gray-700 mb-4">
                <i class="fas fa-file-invoice-dollar text-red-500 mr-2"></i> Ringkasan Pembayaran
            </h4>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal Produk</span>
                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Biaya Pengiriman</span>
                    <span>Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Biaya Admin</span>
                    <span>Rp {{ number_format($transaction->biaya_admin, 0, ',', '.') }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span class="text-red-600">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Aksi -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h4 class="font-bold text-gray-700 mb-4">
                <i class="fas fa-cog text-red-500 mr-2"></i> Aksi
            </h4>
            
            <div class="space-y-3">
                <button onclick="openStatusModal()" 
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-edit mr-2"></i> Update Status
                </button>
                
                <a href="#" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition-colors">
                    <i class="fas fa-print mr-2"></i> Cetak Invoice
                </a>
                
                @if($transaction->status == 'pending')
                <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="success">
                    <button type="submit" 
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fas fa-check mr-2"></i> Tandai sebagai Selesai
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Catatan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h4 class="font-bold text-gray-700 mb-4">
                <i class="fas fa-sticky-note text-red-500 mr-2"></i> Catatan
            </h4>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Catatan Customer:</p>
                    <p class="bg-gray-50 p-3 rounded-lg">{{ $transaction->catatan ?? 'Tidak ada catatan' }}</p>
                </div>
                
                @if($transaction->admin_notes)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Catatan Admin:</p>
                    <p class="bg-red-50 p-3 rounded-lg text-red-700">{{ $transaction->admin_notes }}</p>
                </div>
                @endif
                
                <!-- Form Tambah Catatan -->
                <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $transaction->status }}">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tambah Catatan:</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"></textarea>
                    </div>
                    <button type="submit" 
                            class="mt-2 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-edit text-red-500 mr-2"></i> Update Status
            </h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                <select name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="success" {{ $transaction->status == 'success' ? 'selected' : '' }}>Sukses</option>
                    <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Gagal</option>
                    <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                          placeholder="Alasan perubahan status...">{{ $transaction->admin_notes ?? '' }}</textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeStatusModal()" 
                        class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('statusModal');
    if (event.target == modal) {
        closeStatusModal();
    }
}
</script>
@endpush