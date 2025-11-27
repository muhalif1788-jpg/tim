@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi #' . $transaksi->id_transaksi)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Informasi Transaksi</h5>
    </div>

    <div class="card-body">

        {{-- Informasi utama transaksi --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <strong>ID Transaksi:</strong><br>
                {{ $transaksi->id_transaksi }}
            </div>

            <div class="col-md-4">
                <strong>Nama Pembeli:</strong><br>
                {{ $transaksi->nama_pembeli }}
            </div>

            <div class="col-md-4">
                <strong>Tanggal:</strong><br>
                {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}
            </div>
        </div>

        <hr>

        {{-- Tabel detail produk --}}
        <h5 class="mb-3">Produk yang Dibeli</h5>

        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $i => $detail)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}</td>
                    <td>Rp{{ number_format($detail->produk->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td>Rp{{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total Harga --}}
        <div class="text-end mt-3">
            <h4>Total Akhir: <strong>Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></h4>
        </div>

        <div class="mt-3">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

    </div>
</div>
@endsection
