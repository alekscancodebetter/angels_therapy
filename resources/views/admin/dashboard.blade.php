@extends('admin.layout')

@section('title', 'Overview')

@section('content')
    <div class="admin-page-heading">
        <div><p class="admin-eyebrow">PRACTICE OVERVIEW</p><h1>Dashboard</h1><p>Everything that needs your attention today.</p></div>
        <a class="admin-button admin-button--secondary" href="{{ route('admin.customers.create') }}">+ Add customer</a>
    </div>

    <section class="admin-stats" aria-label="Practice statistics">
        <a class="admin-stat admin-stat--accent" href="{{ route('admin.requests.index', ['status' => 'new']) }}"><span>New requests</span><strong>{{ $stats['newRequests'] }}</strong><small>Waiting for a response</small></a>
        <a class="admin-stat" href="{{ route('admin.calendar') }}"><span>Upcoming appointments</span><strong>{{ $stats['upcomingAppointments'] }}</strong><small>Currently scheduled</small></a>
        <a class="admin-stat" href="{{ route('admin.customers.index') }}"><span>Total customers</span><strong>{{ $stats['customers'] }}</strong><small>Customer records</small></a>
        <a class="admin-stat" href="{{ route('admin.customers.index') }}"><span>New this month</span><strong>{{ $stats['newCustomersThisMonth'] }}</strong><small>New customer records</small></a>
    </section>

    <div class="admin-dashboard-grid">
        <section class="admin-panel admin-panel--wide">
            <div class="admin-panel-heading"><div><p class="admin-eyebrow">TODAY</p><h2>Today’s appointments</h2></div><a href="{{ route('admin.calendar') }}">Full calendar →</a></div>
            @forelse ($todayAppointments as $appointment)
                <a class="admin-appointment-row" href="{{ route('admin.appointments.edit', $appointment) }}">
                    <time>{{ $appointment->starts_at->format('H:i') }}</time>
                    <span><strong>{{ $appointment->customer->name }}</strong><small>{{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage appointment') }} · {{ $appointment->duration_minutes }} min</small></span>
                    <b class="status status--{{ $appointment->status }}">{{ str_replace('_', ' ', $appointment->status) }}</b>
                </a>
            @empty
                <div class="admin-empty"><span>○</span><strong>No appointments today</strong><p>Your day is currently clear.</p></div>
            @endforelse
        </section>

        <section class="admin-panel admin-chart-panel">
            <div class="admin-panel-heading"><div><p class="admin-eyebrow">GROWTH</p><h2>New customers</h2></div><span>Last 6 months</span></div>
            <div class="admin-bar-chart" aria-label="New customers during the last six months">
                @foreach ($monthlyCustomers as $month)
                    <div><span style="height: {{ $month['height'] }}%" title="{{ $month['count'] }} new customers"><b>{{ $month['count'] }}</b></span><small>{{ $month['label'] }}</small></div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-heading"><div><p class="admin-eyebrow">UP NEXT</p><h2>Upcoming</h2></div></div>
            <div class="admin-compact-list">
                @forelse ($upcomingAppointments as $appointment)
                    <a href="{{ route('admin.appointments.edit', $appointment) }}"><time><strong>{{ $appointment->starts_at->format('d') }}</strong>{{ $appointment->starts_at->format('M') }}</time><span><strong>{{ $appointment->customer->name }}</strong><small>{{ $appointment->starts_at->format('H:i') }} · {{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage') }}</small></span></a>
                @empty
                    <p class="admin-muted">No upcoming appointments.</p>
                @endforelse
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-heading"><div><p class="admin-eyebrow">ENQUIRIES</p><h2>Recent requests</h2></div><a href="{{ route('admin.requests.index') }}">View all →</a></div>
            <div class="admin-request-list">
                @forelse ($recentRequests as $appointmentRequest)
                    <div><span><strong>{{ $appointmentRequest->name }}</strong><small>{{ $appointmentRequest->email }} · {{ $appointmentRequest->created_at->diffForHumans() }}</small></span><b class="status status--{{ $appointmentRequest->status }}">{{ $appointmentRequest->status }}</b></div>
                @empty
                    <p class="admin-muted">No appointment requests yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
