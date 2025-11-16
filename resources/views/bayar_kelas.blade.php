@extends('layouts.app')

@section('title', 'Pembayaran Kelas - Flexora')

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
      <img src="https://images.pexels.com/photos/3823039/pexels-photo-3823039.jpeg" alt="Class Image">
    </div>

    <!-- RIGHT -->
    <div class="right">
      <h2>{{ $schedule->studioClass->name }} - Regular Class</h2>
      <div class="price">Rp. {{ number_format($schedule->price, 0, ',', '.') }}</div>
      
      <p><strong>Jadwal:</strong> 
        {{ \Carbon\Carbon::parse($schedule->start_time)->format('l, d M Y (H:i)') }} - 
        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
      </p>
      <p><strong>Coach:</strong> {{ $schedule->instructor }}</p>

      <h4>Data Peserta:</h4>
      <ul>
        <li>Nama: {{ $user->name }}</li>
        <li>Email: {{ $user->email }}</li>
      </ul>

      <form id="paymentForm">
        @csrf
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
        <button type="submit">Lanjut Bayar</button>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const scheduleId = {{ $schedule->id }};
  const paymentForm = document.getElementById('paymentForm');

  // Get token from URL parameter
  const urlParams = new URLSearchParams(window.location.search);
  const token = urlParams.get('token') || '';

  paymentForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    
    // Enrollment sudah dibuat saat user klik konfirmasi
    // Di sini hanya perlu simpan payment method dan redirect ke invoice
    sessionStorage.setItem('enrollment_data', JSON.stringify({
      schedule_id: scheduleId,
      payment_method: paymentMethod
    }));
    
    window.location.href = `/invoice-kelas/${scheduleId}?token=${encodeURIComponent(token)}`;
  });
</script>
@endpush

@endsection
