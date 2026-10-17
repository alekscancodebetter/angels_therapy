@extends('admin.layout')

@section('title', 'Calendar')

@section('content')
    <div class="admin-page-heading">
        <div><p class="admin-eyebrow">APPOINTMENTS</p><h1>Calendar</h1><p>See and manage the month at a glance.</p></div>
        <a class="admin-button admin-button--primary" href="{{ route('admin.appointments.create') }}">+ New appointment</a>
    </div>

    <section class="admin-panel admin-calendar-panel">
        <div class="admin-calendar-toolbar">
            <a href="{{ route('admin.calendar', ['month' => $month->subMonth()->format('Y-m')]) }}" aria-label="Previous month">←</a>
            <div><h2>{{ $month->format('F Y') }}</h2><a href="{{ route('admin.calendar') }}">Today</a></div>
            <a href="{{ route('admin.calendar', ['month' => $month->addMonth()->format('Y-m')]) }}" aria-label="Next month">→</a>
        </div>
        <div class="admin-calendar-weekdays" aria-hidden="true"><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span><span>Sun</span></div>
        <div class="admin-calendar-grid">
            @foreach ($days as $day)
                <div @class(['admin-calendar-day', 'is-outside' => !$day->isSameMonth($month), 'is-today' => $day->isToday()])>
                    <a
                        class="admin-calendar-day__create"
                        href="{{ route('admin.appointments.create', ['date' => $day->format('Y-m-d')]) }}"
                        aria-label="New appointment on {{ $day->format('l, j F Y') }}"
                    ></a>
                    <time datetime="{{ $day->format('Y-m-d') }}">{{ $day->format('j') }}</time>
                    <div class="admin-calendar-events">
                        @foreach ($appointments->get($day->format('Y-m-d'), collect()) as $appointment)
                            <a class="calendar-event calendar-event--{{ $appointment->status }}" href="{{ route('admin.appointments.edit', $appointment) }}"><strong>{{ $appointment->starts_at->format('H:i') }}</strong><span>{{ $appointment->customer->name }}</span><small>{{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage') }}</small></a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
