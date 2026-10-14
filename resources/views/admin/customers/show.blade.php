@extends('admin.layout')

@section('title', $customer->name)

@section('content')
    <div class="admin-page-heading">
        <div><p class="admin-eyebrow">CUSTOMER PROFILE</p><h1>{{ $customer->name }}</h1><p>Customer since {{ $customer->created_at->format('F Y') }}</p></div>
        <div class="admin-heading-actions"><a class="admin-button admin-button--secondary" href="{{ route('admin.customers.edit', $customer) }}">Edit details</a><a class="admin-button admin-button--primary" href="{{ route('admin.appointments.create', ['customer' => $customer->id]) }}">+ Appointment</a></div>
    </div>
    <div class="admin-profile-grid">
        <section class="admin-panel customer-profile-card">
            <span class="customer-avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
            <h2>{{ $customer->name }}</h2>
            <dl><div><dt>Email</dt><dd><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></dd></div><div><dt>Telephone</dt><dd>{{ $customer->phone ?: 'Not provided' }}</dd></div><div><dt>Total appointments</dt><dd>{{ $appointments->total() }}</dd></div></dl>
        </section>
        <section class="admin-panel customer-notes"><div class="admin-panel-heading"><div><p class="admin-eyebrow">PRIVATE</p><h2>Customer notes</h2></div></div><p>{{ $customer->notes ?: 'No private notes have been added.' }}</p></section>
    </div>
    <section class="admin-panel">
        <div class="admin-panel-heading"><div><p class="admin-eyebrow">HISTORY</p><h2>Appointments</h2></div></div>
        <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Date</th><th>Treatment</th><th>Duration</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse ($appointments as $appointment)
                <tr><td><strong>{{ $appointment->starts_at->format('d M Y') }}</strong><small class="table-subtext">{{ $appointment->starts_at->format('H:i') }}</small></td><td>{{ config('treatments.'.$appointment->treatment_slug.'.title', 'Massage appointment') }}</td><td>{{ $appointment->duration_minutes }} min</td><td><b class="status status--{{ $appointment->status }}">{{ str_replace('_', ' ', $appointment->status) }}</b></td><td><a class="admin-table-link" href="{{ route('admin.appointments.edit', $appointment) }}">Open →</a></td></tr>
            @empty
                <tr><td colspan="5"><div class="admin-empty"><strong>No appointments yet</strong><p>Schedule the first appointment for this customer.</p></div></td></tr>
            @endforelse
        </tbody></table></div>
        {{ $appointments->links() }}
    </section>
@endsection
