@extends('admin.auth.layout')

@section('title', 'Create admin account')

@section('content')
    <p class="admin-eyebrow">FIRST-TIME SETUP</p>
    <h2>Create your secure admin account.</h2>
    <p class="admin-auth-intro">This setup closes automatically after the first administrator has been created.</p>

    @include('admin.partials.errors')

    <form class="admin-form" method="POST" action="{{ route('admin.setup.store') }}">
        @csrf
        <label>Your name<input type="text" name="name" value="{{ old('name', 'Villy') }}" maxlength="120" autocomplete="name" required autofocus></label>
        <label>Email address<input type="email" name="email" value="{{ old('email') }}" maxlength="190" autocomplete="email" required></label>
        <label>Password <small>At least 12 characters with letters and numbers</small><input type="password" name="password" autocomplete="new-password" required></label>
        <label>Confirm password<input type="password" name="password_confirmation" autocomplete="new-password" required></label>
        <button class="admin-button admin-button--primary" type="submit">Create private admin</button>
    </form>
@endsection
