@extends('layouts.app')

@section('title', 'Book Class')

@section('content')
    <div class="container">
        <h1>Book Class</h1>
        <p>You are booking the following class:</p>
        <p><strong>Class:</strong> {{ $class->name }}</p>
        <p><strong>Schedule:</strong> {{ $schedule->day }}, {{ $schedule->time }}</p>
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </form>
    </div>
@endsection
