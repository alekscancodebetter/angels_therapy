@extends('admin.layout')

@section('title', 'Edit appointment')

@section('content')
    <div class="admin-page-heading">
        <div>
            <p class="admin-eyebrow">APPOINTMENT</p>
            <h1>{{ $appointment->customer->name }}</h1>
            <p>{{ $appointment->starts_at->format('l, j F Y · H:i') }}</p>
            @if ($appointment->customer->phone)
                <a class="admin-appointment-phone" href="tel:{{ $appointment->customer->phone }}"><span>Telephone</span>{{ $appointment->customer->phone }}</a>
            @else
                <p class="admin-appointment-phone admin-appointment-phone--empty"><span>Telephone</span>Not provided</p>
            @endif
        </div>
        <a class="admin-back-link" href="{{ route('admin.calendar', ['month' => $appointment->starts_at->format('Y-m')]) }}">← Back to calendar</a>
    </div>
    <section class="admin-panel admin-form-panel">
        @include('admin.partials.errors')
        <form class="admin-form" method="POST" action="{{ route('admin.appointments.update', $appointment) }}">
            @csrf @method('PUT')
            @include('admin.appointments.form')
            <div class="admin-form-actions"><a href="{{ route('admin.calendar') }}">Cancel</a><button class="admin-button admin-button--primary" type="submit">Update appointment</button></div>
        </form>
    </section>
@endsection
