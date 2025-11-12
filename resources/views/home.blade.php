@extends('layouts.app')

@section('content')
    <section class="grid grid-cols-1 md:grid-cols-3 h-screen -mt-16">
        {{-- The -mt-16 is to offset the navbar height, adjust if needed --}}
        <div class="md:col-span-2 relative flex items-center p-12 bg-cover bg-center" style="background-image: url('{{ asset('images/left.jpg') }}');">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative max-w-xl text-white z-10">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">Empowering Women Through Movement: Pilates, Pole Dance, and Yoga Classes</h1>
                <p class="text-lg">
                    Let's get one thing clear: olahraga bukan hanya tentang tubuh, tapi juga tentang rasa percaya diri. 
                    Di Flexora, kami menghadirkan ruang aman bagi wanita untuk berlatih Pilates, Pole Dance, dan Yoga 
                    dengan pilihan kelas Reguler, Hijab, dan Private. Tersedia juga kelas berhijab agar setiap wanita merasa nyaman. 
                    Temukan kenyamananmu, pilih gaya yang sesuai, dan rasakan transformasi dari dalam maupun luar.
                </p>
            </div>
        </div>
        <div class="hidden md:block bg-cover bg-center" style="background-image: url('{{ asset('images/right.jpg') }}');"></div>
    </section>
@endsection


