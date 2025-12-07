<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoiceData['nomor'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f5f5f5; padding: 20px; }
        .invoice { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #667eea; padding-bottom: 20px; }
        .header h1 { color: #667eea; margin-bottom: 10px; }
        .info { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info div { flex: 1; }
        .info-title { font-weight: bold; color: #555; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #667eea; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        .total-row { font-weight: bold; font-size: 1.1em; }
        .footer { text-align: center; margin-top: 40px; color: #666; font-size: 0.9em; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
        @media print { .btn { display: none; } body { background: white; } }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <h1>INVOICE PEMBELIAN</h1>
            <p>Kedai Pesisir - Abon Terbaik</p>
            <p>No: {{ $invoiceData['nomor'] }} | Tanggal: {{ $invoiceData['tanggal'] }}</p>
        </div>
        
        <div class="info">
            <div>
                <div class="info-title">Data Penerima:</div>
                <p>{{ $invoiceData['nama_penerima'] }}</p>
                <p>{{ $invoiceData['alamat'] }}</p>
                <p>Telp: {{ $invoiceData['no_telepon'] }}</p>
                @if($invoiceData['catatan'])
                <p>Catatan: {{ $invoiceData['catatan'] }}</p>
                @endif
            </div>
            <div>
                <div class="info-title">Status:</div>
                <p style="color: green; font-weight: bold;">PEMBAYARAN BERHASIL</p>
                <p>Metode: Transfer Bank</p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceData['items'] as $item)
                <tr>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Subtotal:</td>
                    <td>Rp {{ number_format($invoiceData['subtotal'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Pengiriman:</td>
                    <td>Rp {{ number_format($invoiceData['biaya_pengiriman'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;">Admin:</td>
                    <td>Rp {{ number_format($invoiceData['biaya_admin'], 0, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;">TOTAL:</td>
                    <td>Rp {{ number_format($invoiceData['total'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
        
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="color: #667eea; margin-bottom: 10px;">Instruksi Pembayaran:</h3>
            <p>1. Transfer ke BCA: 123-456-7890 (Kedai Pesisir)</p>
            <p>2. Jumlah: <strong>Rp {{ number_format($invoiceData['total'], 0, ',', '.') }}</strong></p>
            <p>3. Konfirmasi pembayaran via WhatsApp: 0812-3456-7890</p>
        </div>
        
        <div class="footer">
            <p>Terima kasih telah berbelanja di Kedai Pesisir!</p>
            <p>Pesanan akan diproses dalam 1x24 jam setelah pembayaran dikonfirmasi.</p>
            <a href="{{ route('customer.products.index') }}" class="btn">Kembali Berbelanja</a>
            <button onclick="window.print()" class="btn">Cetak Invoice</button>
        </div>
    </div>
</body>
</html>