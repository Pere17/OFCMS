<x-guest-layout>
    <p class="text-muted small mb-3">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
                Email Password Reset Link
            </button>
        </div>
    </form>
</x-guest-layout>
