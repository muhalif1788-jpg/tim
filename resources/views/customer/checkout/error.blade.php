@extends('layouts.customer')

@section('title', 'Pembayaran Gagal - Abon Sapi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <div class="text-danger mb-4">
                    <i data-feather="x-circle" style="width: 80px; height: 80px;"></i>
                </div>
                <h2 class="text-danger mb-3">Pembayaran Gagal</h2>
                <p class="text-muted mb-4">{{ session('error', 'Terjadi kesalahan saat proses pembayaran.') }}</p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ route('customer.checkout.index') }}" class="btn btn-primary">
                        <i data-feather="refresh-cw" class="me-2"></i> Coba Lagi
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                        <i data-feather="home" class="me-2"></i> Beranda
                    </a>
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