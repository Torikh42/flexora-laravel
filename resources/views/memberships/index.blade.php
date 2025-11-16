@extends('layouts.app')

@section('title', 'Memberships')

@section('content')
    <div class="container">
        <h1>Memberships</h1>
        <div class="row">
            @foreach ($memberships as $membership)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $membership->name }}</h5>
                            <p class="card-text">{{ $membership->description }}</p>
                            <p class="card-text">Price: ${{ $membership->price }}</p>
                            <p class="card-text">Duration: {{ $membership->duration_days }} days</p>
                            <a href="{{ route('memberships.show', $membership) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection