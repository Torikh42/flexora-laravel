@extends('layouts.app')

@section('title', 'Classes & Memberships')

@section('content')
<div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('images/kelasbg.jpg');">
    <div class="min-h-screen bg-black/50 px-6 py-16 md:py-20">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white text-center mb-12 tracking-tight">
            Pilih Jenis Kelas
        </h1>

        <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8 mb-20 md:mb-24 max-w-6xl mx-auto">
            @forelse ($studio_classes as $class)
                <div onclick="openClassModal({{ $class->id }}, {{ json_encode($class->name) }}, {{ json_encode($class->schedules) }})"
                     class="cursor-pointer bg-white/60 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-6 
                            transition-all duration-300 hover:bg-white/75 hover:scale-[1.03] hover:shadow-2xl">
                    
                    <img src="{{ asset('images/' . ($class->image ?? 'default.jpg')) }}"
                         class="w-full h-48 object-cover rounded-xl mb-5">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-2">{{ $class->name }}</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ Str::limit($class->description, 150) }}</p>
                </div>
            @empty
                <p class="text-white text-center w-full">No classes available at the moment.</p>
            @endforelse
        </div>
        <div class="text-center mb-10 max-w-5xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-lg mb-3">
                Pilih Paket Membership Flexora
            </h2>
            <p class="text-gray-200 text-lg mb-12">
                Tentukan paket yang sesuai kebutuhan latihanmu untuk dapat bergabung ke kelas!
            </p>
            <div class="flex flex-wrap justify-center gap-8">
                @foreach ($memberships as $membership)
                    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl p-8 w-full max-w-xs sm:w-72 relative 
                                hover:-translate-y-2 transition-transform duration-300">
                        <h3 class="text-xl font-semibold mb-2">{{ $membership->name }}</h3>
                        <p class="text-gray-700">{{ $membership->description }}</p>
                        <div class="text-3xl font-bold my-5">Rp. {{ number_format($membership->price, 0, ',', '.') }}</div>
                        <button onclick="purchaseMembership({{ $membership->id }}, '{{ $membership->name }}')" class="block w-full bg-gray-800 text-white py-3 rounded-lg hover:bg-black transition font-semibold cursor-pointer">
                            Pilih Paket
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        </div>
    </div>
    @include('components.modal')
    @endsection
