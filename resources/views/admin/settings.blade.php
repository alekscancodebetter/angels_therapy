@extends('admin.layout')

@section('title', 'Settings')

@section('content')
    <div class="admin-page-heading"><div><p class="admin-eyebrow">ACCOUNT</p><h1>Admin settings</h1><p>Update your sign-in details and password.</p></div></div>
    <section class="admin-panel admin-form-panel">
        @include('admin.partials.errors')
        <form class="admin-form" method="POST" action="{{ route('admin.settings.update') }}">
            @csrf @method('PATCH')
            <div class="admin-form-grid">
                <label>Name<input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" maxlength="120" required></label>
                <label>Email address<input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" maxlength="190" required></label>
                <label>New password <small>Leave empty to keep the current password</small><input type="password" name="password" autocomplete="new-password"></label>
                <label>Confirm new password<input type="password" name="password_confirmation" autocomplete="new-password"></label>
            </div>
            <div class="admin-form-actions"><span></span><button class="admin-button admin-button--primary" type="submit">Save settings</button></div>
        </form>
    </section>
@endsection
