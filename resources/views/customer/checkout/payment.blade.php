@extends('layouts.customer')

@section('content')
<div class="container">
    <h2>Pembayaran</h2>
    <p>Order ID: {{ $orderId }}</p>
    <p>Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
    
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
    
    <form action="{{ route('customer.checkout.finish', ['orderId' => $orderId]) }}" id="submit_form" method="POST">
        @csrf
        <input type="hidden" name="json" id="json_callback">
    </form>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
<script type="text/javascript">
    console.log('Snap Token:', '{{ $snapToken }}');
    
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                document.getElementById('json_callback').value = JSON.stringify(result);
                document.getElementById('submit_form').submit();
            },
            onPending: function(result){
                document.getElementById('json_callback').value = JSON.stringify(result);
                document.getElementById('submit_form').submit();
            },
            onError: function(result){
                document.getElementById('json_callback').value = JSON.stringify(result);
                document.getElementById('submit_form').submit();
            }
        });
    };
</script>
@endsection