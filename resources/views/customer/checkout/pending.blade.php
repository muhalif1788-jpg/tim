@extends('layouts.customer')

@section('title', 'Pembayaran Pending - Abon Sapi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="text-warning mb-4">
                    <i data-feather="clock" style="width: 80px; height: 80px;"></i>
                </div>
                <h2 class="text-warning mb-3">Menunggu Pembayaran</h2>
                <p class="text-muted mb-4">{{ session('message', 'Pembayaran Anda sedang diproses.') }}</p>
                
                <div class="alert alert-info text-start mb-4">
                    <h6><i data-feather="info" class="me-2"></i> Instruksi:</h6>
                    <p class="mb-0">Silakan selesaikan pembayaran sesuai metode yang dipilih. Anda akan mendapat notifikasi email ketika pembayaran berhasil.</p>
                </div>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i data-feather="home" class="me-2"></i> Beranda
                    </a>
                    @if(session('order_id'))
                    <a href="{{ route('customer.checkout.invoice', session('order_id')) }}" class="btn btn-outline-primary">
                        <i data-feather="file-text" class="me-2"></i> Lihat Invoice
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border: none;
}
</style>

<script>
    feather.replace();
</script>
@endsection