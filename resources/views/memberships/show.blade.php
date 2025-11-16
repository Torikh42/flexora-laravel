@extends('layouts.app')

@section('title', $membership->name)

@section('content')
    <div class="container">
        <h1>{{ $membership->name }}</h1>
        <p>{{ $membership->description }}</p>
        <p>Price: ${{ $membership->price }}</p>
        <p>Duration: {{ $membership->duration_days }} days</p>
        <form action="{{ route('memberships.purchase', $membership) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Purchase</button>
        </form>
    </div>
@endsection
