@extends('layouts.customer')

@section('title', 'Tentang Kami - Kedai Pesisir')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tentang.css') }}">
@endsection

@section('content')
<!-- Tentang Kami Hero Section -->
<section class="tentang-hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">TENTANG KAMI</h1>
            <p class="hero-subtitle">Menghadirkan Cita Rasa Autentik Parepare</p>
            <p class="hero-description">Kedai Pesisir adalah pelopor dalam menyajikan abon berkualitas tinggi dengan resep turun-temurun dari Parepare</p>
        </div>
    </div>
</section>

<!-- Tentang Kami Content -->
<section class="tentang-content">
    <div class="container">
        <!-- Visi Section -->
        <div class="section visi-section">
            <div class="section-header">
                <h2>Visi</h2>
            </div>
            <div class="section-content">
                <p class="visi-text">Menjadi penyedia abon berkualitas terbaik di Parepare</p>
            </div>
        </div>

        <!-- Misi Section -->
        <div class="section misi-section">
            <div class="section-header">
                <h2>Misi</h2>
            </div>
            <div class="section-content">
                <ul class="misi-list">
                    <li class="misi-item">
                        <div class="misi-icon">
                            <i data-feather="check-circle"></i>
                        </div>
                        <div class="misi-text">
                            Menyediakan produk abon yang higienis, lezat, dan bernilai gizi tinggi dengan bahan-bahan pilihan dari peternak lokal.
                        </div>
                    </li>
                    <li class="misi-item">
                        <div class="misi-icon">
                            <i data-feather="check-circle"></i>
                        </div>
                        <div class="misi-text">
                            Mengembangkan sistem penjualan online yang memudahkan pelanggan untuk membeli produk dengan aman dan efisien.
                        </div>
                    </li>
                    <li class="misi-item">
                        <div class="misi-icon">
                            <i data-feather="check-circle"></i>
                        </div>
                        <div class="misi-text">
                            Mendukung pemberdayaan pelaku UMKM lokal melalui kolaborasi dalam rantai pasok bahan baku.
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Team Section -->
        <div class="section team-section">
            <div class="section-header">
                <h2>Tim Kami</h2>
            </div>
            <div class="section-content">
                <div class="team-grid">
                    <!-- Manager -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i data-feather="user"></i>
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">ALIF</h3>
                            <p class="member-position">Manager</p>
                        </div>
                    </div>

                    <!-- Chef -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i data-feather="user"></i>
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">ALIF</h3>
                            <p class="member-position">Chef</p>
                        </div>
                    </div>

                    <!-- Marketing Manager -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i data-feather="user"></i>
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">ALIF</h3>
                            <p class="member-position">Marketing Manager</p>
                        </div>
                    </div>

                    <!-- Staff -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i data-feather="user"></i>
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">ALIF</h3>
                            <p class="member-position">Staff</p>
                        </div>
                    </div>

                    <!-- Supply Chain Management -->
                    <div class="team-member">
                        <div class="member-avatar">
                            <i data-feather="user"></i>
                        </div>
                        <div class="member-info">
                            <h3 class="member-name">ALIF</h3>
                            <p class="member-position">Supply Chain Management</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sejarah Section -->
        <div class="section sejarah-section">
            <div class="section-header">
                <h2>Sejarah Kami</h2>
            </div>
            <div class="section-content">
                <p class="sejarah-text">
                    Kedai Pesisir didirikan dengan semangat untuk melestarikan cita rasa autentik abon khas Parepare. 
                    Dengan dedikasi dan komitmen terhadap kualitas, kami terus berinovasi untuk memberikan pengalaman 
                    kuliner terbaik kepada pelanggan setia kami.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    feather.replace();
</script>
@endsection