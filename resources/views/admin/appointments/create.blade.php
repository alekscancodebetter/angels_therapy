@extends('admin.layout')

@section('title', 'New appointment')

@section('content')
    <div class="admin-page-heading"><div><p class="admin-eyebrow">CALENDAR</p><h1>New appointment</h1><p>Schedule treatment time for a customer.</p></div><a class="admin-back-link" href="{{ route('admin.calendar') }}">← Back to calendar</a></div>
    <section class="admin-panel admin-form-panel">
        @include('admin.partials.errors')
        <form class="admin-form" method="POST" action="{{ route('admin.appointments.store') }}">
            @csrf
            @include('admin.appointments.form')
            <div class="admin-form-actions"><a href="{{ route('admin.calendar') }}">Cancel</a><button class="admin-button admin-button--primary" type="submit">Schedule appointment</button></div>
        </form>
    </section>
@endsection
