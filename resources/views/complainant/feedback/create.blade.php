@extends('layouts.app')

@section('page-title', 'Give Feedback')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('complainant.feedback.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input id="subject" name="subject" type="text" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required maxlength="255">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Rating</label>
                            <div class="star-rating">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" class="btn-check" @checked(old('rating') == $i) required>
                                    <label for="rating-{{ $i }}" class="btn btn-outline-warning btn-sm me-1"><i class="bi bi-star-fill"></i> {{ $i }}</label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="5" class="form-control @error('message') is-invalid @enderror" required minlength="10">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="form-check-input" @checked(old('is_anonymous'))>
                            <label class="form-check-label" for="is_anonymous">Submit anonymously</label>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" style="background-color: var(--ofcms-primary); border-color: var(--ofcms-primary);">
                                Submit Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
