@extends('admin.layout')

@section('title', 'Appointment requests')

@section('content')
    <div class="admin-page-heading"><div><p class="admin-eyebrow">WEBSITE ENQUIRIES</p><h1>Appointment requests</h1><p>Messages sent through the public website.</p></div></div>
    <nav class="admin-filter-tabs" aria-label="Filter requests"><a href="{{ route('admin.requests.index') }}" @class(['is-active' => !$status])>All</a>@foreach (['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $value => $label)<a href="{{ route('admin.requests.index', ['status' => $value]) }}" @class(['is-active' => $status === $value])>{{ $label }}</a>@endforeach</nav>
    <div class="admin-request-cards">
        @forelse ($requests as $appointmentRequest)
            <article class="admin-panel admin-request-card">
                <header><div class="customer-avatar customer-avatar--small">{{ strtoupper(substr($appointmentRequest->name, 0, 1)) }}</div><div><h2>{{ $appointmentRequest->name }}</h2><a href="mailto:{{ $appointmentRequest->email }}">{{ $appointmentRequest->email }}</a><small>{{ $appointmentRequest->created_at->format('d M Y · H:i') }}</small></div><b class="status status--{{ $appointmentRequest->status }}">{{ $appointmentRequest->status }}</b></header>
                <p>{{ $appointmentRequest->message }}</p>
                <footer>
                    <a class="admin-button admin-button--secondary" href="mailto:{{ $appointmentRequest->email }}">Reply by email</a>
                    <form method="POST" action="{{ route('admin.requests.update', $appointmentRequest) }}">@csrf @method('PATCH')<label>Status<select name="status" onchange="this.form.submit()">@foreach (['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'] as $value => $label)<option value="{{ $value }}" @selected($appointmentRequest->status === $value)>{{ $label }}</option>@endforeach</select></label></form>
                </footer>
            </article>
        @empty
            <section class="admin-panel admin-empty"><strong>No requests in this view</strong><p>New website enquiries will appear here.</p></section>
        @endforelse
    </div>
    {{ $requests->links() }}
@endsection
