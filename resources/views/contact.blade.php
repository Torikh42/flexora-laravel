@extends('layouts.app')

@section('title', 'Contact - Flexora')

@section('content')
<div class="px-4 md:px-8">
  <div class="max-w-4xl mx-auto py-12 md:py-16">
    <!-- Header Section -->
    <div class="text-center mb-12">
      <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Hubungi Kami</h1>
      <p class="text-lg text-gray-600">Kami siap membantu Anda dengan pertanyaan atau kebutuhan apapun</p>
    </div>

    <!-- Contact Info Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
      @foreach($contacts as $contact)
        <div class="bg-white rounded-2xl p-8 shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-100">
          <div class="text-4xl mb-4">{{ $contact['icon'] }}</div>
          <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $contact['title'] }}</h3>
          <p class="text-gray-600 text-sm leading-relaxed">{{ $contact['content'] }}</p>
        </div>
      @endforeach
    </div>

    <!-- Contact Form Section -->
    <div class="bg-linear-to-r from-amber-50 to-orange-50 rounded-3xl p-8 md:p-12 shadow-lg">
      <h2 class="text-3xl font-bold text-gray-900 mb-2">Kirim Pesan</h2>
      <p class="text-gray-600 mb-8">Isi form di bawah ini dan kami akan menghubungi Anda segera</p>
      
      <form class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nama -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" placeholder="Masukkan nama Anda"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-900 transition-colors">
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <input type="email" placeholder="Masukkan email Anda"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-900 transition-colors">
          </div>

          <!-- Telepon -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
            <input type="tel" placeholder="Masukkan nomor telepon"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-900 transition-colors">
          </div>

          <!-- Subjek -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek</label>
            <input type="text" placeholder="Subjek pesan Anda"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-900 transition-colors">
          </div>
        </div>

        <!-- Pesan -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Pesan</label>
          <textarea rows="6" placeholder="Tulis pesan Anda di sini..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-900 transition-colors resize-none"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button type="submit"
                  class="px-8 py-3 bg-amber-900 text-white font-semibold rounded-lg hover:bg-amber-800 transition-colors duration-300 shadow-md">
            Kirim Pesan
          </button>
        </div>
      </form>
    </div>

    <!-- Additional Info -->
    <div class="mt-12 bg-white rounded-2xl p-8 md:p-12 border border-gray-100">
      <h3 class="text-2xl font-bold text-gray-900 mb-6">Informasi Tambahan</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <h4 class="font-bold text-gray-800 mb-2">Tentang Flexora</h4>
          <p class="text-gray-600 text-sm leading-relaxed">
            Flexora adalah studio fitness terkemuka yang menawarkan berbagai kelas olahraga berkualitas tinggi dengan instruktur profesional. Kami berkomitmen untuk membantu Anda mencapai tujuan kesehatan dan kebugaran.
          </p>
        </div>
        <div>
          <h4 class="font-bold text-gray-800 mb-2">Mengapa Memilih Flexora?</h4>
          <ul class="text-gray-600 text-sm space-y-2">
            <li>✓ Instruktur berpengalaman dan bersertifikat</li>
            <li>✓ Fasilitas modern dan lengkap</li>
            <li>✓ Program kelas yang beragam</li>
            <li>✓ Harga membership yang kompetitif</li>
            <li>✓ Komunitas yang supportif</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Handle form submission
  document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('✅ Terima kasih! Pesan Anda telah dikirim. Kami akan menghubungi Anda segera.');
    this.reset();
  });
</script>
@endpush
