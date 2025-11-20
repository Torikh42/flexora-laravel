@extends('layouts.app')

@section('title', 'Pembayaran Membership - Flexora')

@push('styles')
<style>
  .container-payment {
    display: flex;
    flex-direction: row;
    max-width: 1000px;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .left {
    width: 50%;
  }

  .left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .right {
    width: 50%;
    padding: 30px;
  }

  h2 { 
    margin-top: 0; 
    color: #5a4636; 
  }

  .price { 
    font-size: 1.5rem; 
    font-weight: bold; 
    margin: 10px 0; 
    color: #d4691f;
  }

  ul { 
    margin: 10px 0 20px 20px; 
  }

  .payment { 
    margin: 15px 0; 
  }

  .payment label { 
    display: block; 
    margin-bottom: 10px; 
    cursor: pointer;
  }

  #paymentForm button {
    width: 100%;
    padding: 12px;
    background: #5a4636;
    color: white;
    font-size: 15px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
  }

  #paymentForm button:hover {
    background: #7a6047;
  }

  @media (max-width: 768px) {
    .container-payment {
      flex-direction: column;
    }

    .left, .right {
      width: 100%;
    }

    .left {
      display: none;
    }
  }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e') center/cover;">
  <div class="container-payment">
    <!-- LEFT -->
    <div class="left">
      <img src="https://images.pexels.com/photos/3823039/pexels-photo-3823039.jpeg" alt="Membership Image">
    </div>

    <!-- RIGHT -->
    <div class="right">
      <h2>{{ $membership->name }}</h2>
      <div class="price">Rp. {{ number_format($membership->price, 0, ',', '.') }}</div>
      
      <p><strong>Durasi:</strong> {{ $membership->duration_days }} hari</p>
      <p><strong>Deskripsi:</strong> {{ $membership->description }}</p>

      <h4>Data Pembeli:</h4>
      <ul>
        <li>Nama: {{ $user->name }}</li>
        <li>Email: {{ $user->email }}</li>
      </ul>

      <form id="paymentForm" method="POST" action="{{ route('memberships.processPayment', ['membership' => $membership->id]) }}">
        @csrf
        <!-- Hidden input untuk pass JWT token -->
        <input type="hidden" id="tokenInput" name="token" value="">
        
        <div class="payment">
          <h4>Metode Pembayaran:</h4>
          <label>
            <input type="radio" name="payment_method" value="transfer_bank" required> 
            Transfer Bank
          </label>
          <label>
            <input type="radio" name="payment_method" value="ewallet"> 
            E-Wallet
          </label>
          <label>
            <input type="radio" name="payment_method" value="qris"> 
            QRIS
          </label>
        </div>
        <button type="submit">Bayar Sekarang</button>
      </form>
    </div>
  </div>
</div>
</div>

<script>
  // Populate JWT token dari localStorage sebelum form di-submit
  document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const token = localStorage.getItem('auth_token');
    if (token) {
      document.getElementById('tokenInput').value = token;
    } else {
      // Jika tidak ada token, redirect ke login
      e.preventDefault();
      alert('Session Anda telah berakhir. Silakan login kembali.');
      window.location.href = '{{ route("login") }}';
    }
  });
</script>
@endsection
