@extends('layouts.app')

@section('page-title', 'Profile')

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-1">Profile Information</h5>
                    <p class="text-muted small mb-3">Update your account's profile information and email address.</p>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-1">Update Password</h5>
                    <p class="text-muted small mb-3">Ensure your account is using a long, random password to stay secure.</p>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mt-4 border-danger">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-1 text-danger">Delete Account</h5>
                    <p class="text-muted small mb-3">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
