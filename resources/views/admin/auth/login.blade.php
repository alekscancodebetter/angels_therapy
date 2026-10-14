@extends('admin.auth.layout')

@section('title', 'Admin sign in')

@section('content')
    <p class="admin-eyebrow">WELCOME BACK</p>
    <h2>Sign in to your practice.</h2>
    <p class="admin-auth-intro">Use your private admin account to manage appointments and customer records.</p>

    @include('admin.partials.errors')

    <form class="admin-form" method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <label>Email address<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
        <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
        <label class="admin-check"><input type="checkbox" name="remember" value="1"> Keep me signed in</label>
        <button class="admin-button admin-button--primary" type="submit">Sign in</button>
    </form>
    <a class="admin-back-link" href="{{ route('home') }}">← Back to the website</a>
@endsection
