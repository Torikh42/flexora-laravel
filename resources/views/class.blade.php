@extends('layouts.app')

@section('title', 'Classes & Memberships')

@section('content')
{{-- 
    PERUBAHAN:
    - Menambahkan `bg-fixed` agar background image tidak ikut scroll (parallax effect).
--}}
<div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('images/kelasbg.jpg');">
    {{-- 
        PERUBAHAN:
        - Mengganti `backdrop-brightness-75` menjadi `bg-black/50` untuk overlay yang lebih halus.
        - Menambah padding vertikal `py-16 md:py-20` agar lebih lega.
    --}}
    <div class="min-h-screen bg-black/50 px-6 py-16 md:py-20">

        {{-- 
            PERUBAHAN:
            - Membuat heading `<h1>` lebih besar dan tebal (`md:text-5xl font-extrabold`).
            - Menambah `tracking-tight` untuk spasi huruf yang lebih modern.
            - Menambah `mb-12` untuk spasi ke bawah.
        --}}
        <h1 class="text-4xl md:text-5xl font-extrabold text-white text-center mb-12 tracking-tight">
            Pilih Jenis Kelas
        </h1>

        <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8 mb-20 md:mb-24 max-w-6xl mx-auto">
            @forelse ($classes as $class)
                {{-- 
                    PERUBAHAN:
                    - `bg-white/70` -> `bg-white/60` (lebih transparan).
                    - `backdrop-blur-md` -> `backdrop-blur-xl` (lebih nge-blur).
                    - Menambah `border border-white/20` untuk "pinggiran kaca" yang jelas.
                    - `p-5` -> `p-6` (padding lebih besar).
                    - Efek hover diubah: `hover:bg-white/75` (sedikit lebih cerah) dan `hover:scale-[1.03]` (sedikit lebih halus).
                    - Menambah `transition-all duration-300`.
                --}}
                <div onclick="location.href='{{ route('classes.show', $class->id) }}'"
                     class="cursor-pointer bg-white/60 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 
                            transition-all duration-300 hover:bg-white/75 hover:scale-[1.03] hover:shadow-2xl">
                    
                    <img src="{{ asset('images/' . ($class->image ?? 'default.jpg')) }}"
                         class="w-full h-48 object-cover rounded-xl mb-5">
                    
                    {{-- 
                        PERUBAHAN:
                        - `text-xl` -> `text-2xl` agar judul kelas lebih jelas.
                    --}}
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ $class->name }}</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ Str::limit($class->description, 150) }}</p>
                </div>
            @empty
                <p class="text-white text-center w-full">No classes available at the moment.</p>
            @endforelse
        </div>

        <div class="text-center mb-10 max-w-5xl mx-auto">
            {{-- 
                PERUBAHAN:
                - `text-3xl` -> `md:text-4xl font-extrabold` (lebih tebal dan besar).
            --}}
            <h2 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-lg mb-3">
                Pilih Paket Membership Flexora
            </h2>
            {{-- 
                PERUBAHAN:
                - `text-gray-100` -> `text-gray-200 text-lg` (sedikit lebih cerah dan besar agar mudah dibaca).
            --}}
            <p class="text-gray-200 text-lg mb-12">
                Tentukan paket yang sesuai kebutuhan latihanmu untuk dapat bergabung ke kelas!
            </p>

            <div class="flex flex-wrap justify-center gap-8">
                
                {{-- 
                    PERUBAHAN CARD MEMBERSHIP:
                    - `bg-white/70` -> `bg-white/80` (sedikit lebih solid).
                    - `backdrop-blur-lg` -> `backdrop-blur-xl`.
                    - Menambah `border border-white/20`.
                    - `w-72` -> `w-full max-w-xs sm:w-72` (lebih responsif di mobile).
                    - `transition-transform` agar hover lebih halus.
                --}}
                
                <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-8 w-full max-w-xs sm:w-72 relative 
                            hover:-translate-y-2 transition-transform duration-300">
                    <h3 class="text-xl font-semibold mb-2">Trial 2 Minggu</h3>
                    <p class="text-gray-700">3x Pertemuan</p>
                    <div class="text-3xl font-bold my-5">Rp. 150.000</div>
                    {{-- 
                        PERUBAHAN TOMBOL:
                        - `bg-gray-900` -> `bg-gray-800`.
                        - `py-2` -> `py-3` (tombol lebih "tebal").
                        - Menambah `font-semibold`.
                    --}}
                    <a href="#" class="block bg-gray-800 text-white py-3 rounded-lg hover:bg-black transition font-semibold">
                        Pilih Paket
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-8 w-full max-w-xs sm:w-72 relative 
                            hover:-translate-y-2 transition-transform duration-300">
                    {{-- 
                        PERUBAHAN TAG:
                        - Mengganti `bg-black` menjadi `bg-pink-600` untuk "pop of color" yang menarik.
                        - `top-3 right-3` -> `absolute -top-3 -right-3` (posisi di luar card, lebih menarik).
                        - *Update*: Posisi di luar card mungkin terlalu ramai, kita buat di dalam tapi lebih menonjol.
                        - `top-3 right-3` -> `top-4 right-4` (sedikit penyesuaian).
                        - `font-bold` -> `font-semibold`.
                    --}}
                    <span class="absolute top-4 right-4 bg-pink-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        ⭐ Populer
                    </span>
                    <h3 class="text-xl font-semibold mb-2">Bulanan</h3>
                    <p class="text-gray-700">6-7x Pertemuan / bulan</p>
                    <div class="text-3xl font-bold my-5">Rp. 500.000</div>
                    {{-- 
                        PERUBAHAN TOMBOL (CTA):
                        - Menggunakan warna pink agar selaras dengan tag "Populer".
                    --}}
                    <a href="#" class="block bg-pink-600 text-white py-3 rounded-lg hover:bg-pink-700 transition font-semibold">
                        Pilih Paket
                    </a>
                </div>

                <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-8 w-full max-w-xs sm:w-72 relative 
                            hover:-translate-y-2 transition-transform duration-300">
                    <h3 class="text-xl font-semibold mb-2">Private / Premium</h3>
                    <p class="text-gray-700">Kelas private dengan coach</p>
                    <div class="text-3xl font-bold my-5">Rp. 1.000.000+</div>
                    <a href="#" class="block bg-gray-800 text-white py-3 rounded-lg hover:bg-black transition font-semibold">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection