@extends('layouts.app')

@section('content')

@push('styles')
<style>
/* ===============================
   APPLE CARD – PREMIUM FEEL
================================ */
.apple-card {
    background: rgba(255, 255, 255, 0.94);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid rgba(229,231,235,.9);
    box-shadow:
        0 10px 30px rgba(0,0,0,.08),
        0 2px 6px rgba(0,0,0,.04);
}

/* Inputs */
.apple-input {
    border-radius: 14px;
    padding: 12px 14px;
    border: 1px solid #e5e7eb;
}

.apple-input:focus {
    border-color: #122b50;
    box-shadow: 0 0 0 3px rgba(18,43,80,.15);
}

/* ===============================
   LOGIN HERO (PREVIEW + REAL)
================================ */
.login-preview {
    display: flex;
    min-height: 460px;
}

.login-hero {
    width: 50%;
    position: relative;
    overflow: hidden;
}

.login-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
}

.login-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,.45);
}

.login-hero-text {
    position: absolute;
    bottom: 0;
    left: 0;
    padding: 24px;
    color: #fff;
}

.login-form-preview {
    width: 50%;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.login-form-preview input,
.login-form-preview button {
    pointer-events: none;
}

@media (max-width: 992px) {
    .login-preview {
        flex-direction: column;
    }
    .login-hero {
        display: none;
    }
    .login-form-preview {
        width: 100%;
    }
}
</style>
@endpush

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Login Screen – Hero Section</h4>
        <span class="text-muted small">Customize login appearance</span>
    </div>

    <div class="row g-4">

        {{-- ================= FORM ================= --}}
        <div class="col-lg-6">
            <div class="apple-card p-4">

                <h6 class="fw-bold mb-3">Hero Content</h6>

                <form method="POST"
                      action="{{ route('login-hero.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text"
                               name="title"
                               value="{{ old('title', $hero->title ?? '') }}"
                               class="form-control apple-input"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Main Text</label>
                        <textarea name="text"
                                  rows="4"
                                  class="form-control apple-input">{{ old('text', $hero->text ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text"
                               name="tagline"
                               value="{{ old('tagline', $hero->tagline ?? '') }}"
                               class="form-control apple-input">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Background Image</label>
                        <input type="file"
                               name="image"
                               class="form-control apple-input"
                               accept="image/*">
                        <small class="text-muted">
                            JPG / PNG / WEBP · Stored in DigitalOcean Spaces
                        </small>
                    </div>

                    <button class="btn btn-secondary rounded-pill px-4">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </form>

            </div>
        </div>

        {{-- ================= LIVE LOGIN PREVIEW ================= --}}
        <div class="col-lg-6">
            <div class="apple-card p-0 overflow-hidden">

                <div class="login-preview">

                    {{-- LEFT HERO --}}
                    <div class="login-hero">
                        <img src="{{ $hero && $hero->image
                                ? Storage::disk('spaces')->url($hero->image)
                                : '/6.webp' }}"
                             class="login-hero-img">

                        <div class="login-overlay"></div>

                        <div class="login-hero-text">
                            <h4 class="fw-bold mb-2" style="color: white">
                                {{ $hero->title ?? 'Smart Loan Management' }}
                            </h4>

                            <p class="small mb-2">
                                {{ $hero->text ?? 'Secure. Fast. Reliable.' }}
                            </p>

                            <span class="text-uppercase small opacity-75">
                                {{ $hero->tagline ?? 'Built for professionals' }}
                            </span>
                        </div>
                    </div>

                    {{-- RIGHT FORM --}}
                    <div class="login-form-preview">

                        <div class="text-center mb-4">
                            <img src="/logo.webp" width="160">
                        </div>

                        <div class="mb-3">
                            <input type="email"
                                   class="form-control apple-input"
                                   placeholder="Email"
                                   disabled>
                        </div>

                        <div class="mb-4">
                            <input type="password"
                                   class="form-control apple-input"
                                   placeholder="Password"
                                   disabled>
                        </div>

                        <button class="btn btn-secondary rounded-pill w-100 py-2" disabled>
                            Login
                        </button>

                        <p class="text-center text-muted small mt-4 mb-0">
                            Powered by <strong>Neurasoft Technologies</strong>
                        </p>

                    </div>

                </div>

                <div class="p-3 border-top text-center">
                    <span class="badge bg-success">Live Login Preview</span>
                    <span class="text-muted small ms-2">
                        Matches actual login screen
                    </span>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
