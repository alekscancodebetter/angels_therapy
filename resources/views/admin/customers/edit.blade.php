@extends('admin.layout')

@section('title', 'Edit customer')

@section('content')
    <div class="admin-page-heading"><div><p class="admin-eyebrow">CUSTOMERS</p><h1>Edit {{ $customer->name }}</h1><p>Keep contact information and private notes up to date.</p></div><a class="admin-back-link" href="{{ route('admin.customers.show', $customer) }}">← Customer profile</a></div>
    <section class="admin-panel admin-form-panel">
        @include('admin.partials.errors')
        <form class="admin-form" method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf @method('PUT')
            @include('admin.customers.form')
            <div class="admin-form-actions"><a href="{{ route('admin.customers.show', $customer) }}">Cancel</a><button class="admin-button admin-button--primary" type="submit">Update customer</button></div>
        </form>
    </section>
@endsection
