@extends('admin.layout')

@section('title', 'Customers')

@section('content')
    <div class="admin-page-heading">
        <div><p class="admin-eyebrow">CUSTOMER RECORDS</p><h1>Customers</h1><p>Contact details, notes and appointment history.</p></div>
        <a class="admin-button admin-button--primary" href="{{ route('admin.customers.create') }}">+ Add customer</a>
    </div>

    <section class="admin-panel">
        <form class="admin-search" method="GET" action="{{ route('admin.customers.index') }}">
            <label><span>⌕</span><input type="search" name="q" value="{{ $search }}" placeholder="Search name, email or phone"></label>
            <button class="admin-button admin-button--secondary" type="submit">Search</button>
            @if ($search !== '')<a href="{{ route('admin.customers.index') }}">Clear</a>@endif
        </form>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Customer</th><th>Phone</th><th>Appointments</th><th>Added</th><th></th></tr></thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td><a class="customer-cell" href="{{ route('admin.customers.show', $customer) }}"><span>{{ strtoupper(substr($customer->name, 0, 1)) }}</span><strong>{{ $customer->name }}<small>{{ $customer->email ?: 'No email address' }}</small></strong></a></td>
                            <td>{{ $customer->phone ?: '—' }}</td>
                            <td>{{ $customer->appointments_count }}</td>
                            <td>{{ $customer->created_at->format('d M Y') }}</td>
                            <td><a class="admin-table-link" href="{{ route('admin.customers.show', $customer) }}">View →</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="admin-empty"><strong>No customers found</strong><p>Add a customer or change your search.</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $customers->links() }}
    </section>
@endsection
