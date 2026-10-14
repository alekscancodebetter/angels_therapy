@extends('admin.layout')

@section('title', 'Add customer')

@section('content')
    <div class="admin-page-heading"><div><p class="admin-eyebrow">CUSTOMERS</p><h1>Add customer</h1><p>Create a private customer record.</p></div><a class="admin-back-link" href="{{ route('admin.customers.index') }}">← Back to customers</a></div>
    <section class="admin-panel admin-form-panel">
        @include('admin.partials.errors')
        <form class="admin-form" method="POST" action="{{ route('admin.customers.store') }}">
            @csrf
            @include('admin.customers.form')
            <div class="admin-form-actions"><a href="{{ route('admin.customers.index') }}">Cancel</a><button class="admin-button admin-button--primary" type="submit">Save customer</button></div>
        </form>
    </section>
@endsection
