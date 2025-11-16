@extends('layouts.app')

@section('title', 'Invoice Kelas - Flexora')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<style>
  .container-invoice {
    display: flex;
    gap: 30px;
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
  }

  .left {
    flex: 1;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .right {
    flex: 1;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
  }

  .success-box {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: bold;
  }

  h2 {
    color: #5a4636;
    margin-top: 0;
  }

  .booking-box h3 {
    color: #5a4636;
    margin-top: 0;
  }

  .qr-section {
    margin: 20px 0;
  }

  canvas {
    border: 1px solid #ddd;
    border-radius: 8px;
    max-width: 200px;
  }

  .btn-download {
    display: inline-block;
    margin: 10px;
    padding: 10px 20px;
    background: #5a4636;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
  }

  .btn-download:hover {
    background: #7a6047;
  }

  @media (max-width: 768px) {
    .container-invoice {
      flex-direction: column;
    }
  }
</style>
@endpush

@section('content')
<div class="min-h-screen py-10" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e') center/cover;">
  <div class="container-invoice">
    <!-- LEFT -->
    <div class="left">
      <div class="success-box">
        ✅ Pendaftaran Kelas Berhasil! <br>
        Terima kasih telah mendaftar kelas di Flexora. Nikmati pengalaman olahraga terbaik bersama coach profesional kami. 💪
      </div>

      <div class="booking-box">
        <h3>Detail Kelas</h3>
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Kelas:</strong> {{ $schedule->studioClass->name }}</p>
        <p><strong>Jadwal:</strong> 
          {{ \Carbon\Carbon::parse($schedule->start_time)->format('l, d M Y (H:i)') }} - 
          {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
        </p>
        <p><strong>Coach:</strong> {{ $schedule->instructor }}</p>
        <p><strong>Harga:</strong> Rp. {{ number_format($schedule->price, 0, ',', '.') }}</p>
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
      <h2>Tiket Digital</h2>
      <p style="color: #666;">Simpan atau download tiket ini sebagai bukti pendaftaran</p>

      <div class="qr-section">
        <canvas id="qrcode" style="width: 200px; height: 200px;"></canvas>
      </div>

      <div>
        <button class="btn-download" onclick="downloadTicket()">📥 Download Tiket</button>
        <button class="btn-download" onclick="downloadInvoice()">📄 Download Invoice</button>
      </div>

      <p style="margin-top: 20px; color: #999; font-size: 12px;">
        Tunjukkan QR code ini saat sampai di studio untuk checkin
      </p>
    </div>
  </div>
</div>

@push('scripts')
<script>
  const scheduleData = {
    id: {{ $schedule->id }},
    className: '{{ $schedule->studioClass->name }}',
    startTime: '{{ $schedule->start_time }}',
    endTime: '{{ $schedule->end_time }}',
    instructor: '{{ $schedule->instructor }}',
    price: {{ $schedule->price }},
    userName: '{{ $user->name }}',
    userEmail: '{{ $user->email }}'
  };

  // Generate QR Code
  new QRious({
    element: document.getElementById("qrcode"),
    value: `Flexora | ${scheduleData.className} | ${scheduleData.startTime} | ${scheduleData.userName}`,
    size: 200
  });

  function downloadTicket() {
    const canvas = document.getElementById("qrcode");
    const link = document.createElement("a");
    link.href = canvas.toDataURL("image/png");
    link.download = `tiket-flexora-${scheduleData.id}.png`;
    link.click();
  }

  function downloadInvoice() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.setFontSize(16);
    doc.text("INVOICE PENDAFTARAN KELAS", 20, 20);

    doc.setFontSize(10);
    doc.text(`Nama: ${scheduleData.userName}`, 20, 40);
    doc.text(`Email: ${scheduleData.userEmail}`, 20, 48);
    doc.text(`Kelas: ${scheduleData.className}`, 20, 56);
    doc.text(`Jadwal: ${scheduleData.startTime}`, 20, 64);
    doc.text(`Coach: ${scheduleData.instructor}`, 20, 72);
    doc.text(`Harga: Rp. ${scheduleData.price.toLocaleString('id-ID')}`, 20, 80);

    doc.save(`invoice-flexora-${scheduleData.id}.pdf`);
  }
</script>
@endpush

@endsection
