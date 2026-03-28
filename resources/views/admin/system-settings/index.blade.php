@extends('layouts.app')

@section('content')

<style>
/* ===============================
   🍎 SETTINGS CONTAINER
================================ */
.settings-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

/* ===============================
   🍎 SETTINGS CARD
================================ */
.settings-card {
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 20px 45px rgba(0,0,0,.08);
    overflow: hidden;
}

/* ===============================
   🍎 SETTINGS ROW
================================ */
.setting-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 22px;
    transition: background .2s ease;
}

.setting-row:not(:last-child) {
    border-bottom: 1px solid #eef2f7;
}

.setting-row:hover {
    background: #fafafa;
}

/* ===============================
   🍎 TEXT
================================ */
.setting-title {
    font-weight: 600;
    letter-spacing: -0.01em;
}

.setting-desc {
    font-size: 13px;
    color: #6b7280;
    margin-top: 2px;
}

/* ===============================
   🍎 SWITCH
================================ */
.switch {
    position: relative;
    display: inline-block;
    width: 54px;
    height: 30px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    inset: 0;
    background: #e5e7eb;
    border-radius: 999px;
    transition: .3s;
}

.slider:before {
    content: "";
    position: absolute;
    height: 24px;
    width: 24px;
    left: 3px;
    bottom: 3px;
    background: #fff;
    border-radius: 50%;
    transition: .3s;
    box-shadow: 0 3px 8px rgba(0,0,0,.25);
}

.switch input:checked + .slider {
    background: linear-gradient(135deg,#16a34a,#22c55e);
}

.switch input:checked + .slider:before {
    transform: translateX(24px);
}

/* ===============================
   🍎 INPUT
================================ */
.setting-input {
    max-width: 180px;
    border-radius: 999px;
    font-weight: 600;
    text-align: center;
}

/* ===============================
   🍎 SAVE BUTTON
================================ */
.save-btn {
    background: linear-gradient(135deg,#9b0000,#7d0000);
    border: none;
    color: #fff;
    border-radius: 999px;
    padding: 12px 26px;
    font-weight: 700;
    box-shadow: 0 12px 30px rgba(155,0,0,.35);
    transition: all .2s ease;
}

.save-btn:hover {
    background: linear-gradient(135deg,#b00000,#8b0000);
    transform: translateY(-1px);
    box-shadow: 0 16px 36px rgba(155,0,0,.45);
}

.backup-card {
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 20px 45px rgba(0,0,0,.08);
    padding: 24px;
}

.backup-icon {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    box-shadow: 0 12px 28px rgba(37,99,235,.24);
}

.backup-btn {
    border-radius: 999px;
    font-weight: 700;
    padding: 10px 18px;
}
</style>

<div class="container settings-wrapper">

    {{-- HEADER --}}
    <div class="mb-4">
        <h3 class="fw-bold mb-1">System Settings</h3>
        <p class="text-muted mb-0">
            Control global behavior of the system
        </p>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success rounded-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger rounded-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- SETTINGS FORM --}}
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="settings-card">

            @foreach($settings as $setting)
                <div class="setting-row">

                    <div>
                        <div class="setting-title">
                            {{ Str::headline($setting->key) }}
                        </div>
                        <div class="setting-desc">
                            {{ $setting->description }}
                        </div>
                    </div>

                    <div>
                        @if($setting->type === 'boolean')
                            <label class="switch">
                                <input type="checkbox"
                                       name="settings[{{ $setting->id }}]"
                                       {{ $setting->value === 'true' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        @else
                            <input type="text"
                                   name="settings[{{ $setting->id }}]"
                                   value="{{ $setting->value }}"
                                   class="form-control setting-input">
                        @endif
                    </div>

                </div>
            @endforeach

        </div>

        {{-- ACTION --}}
        <div class="mt-4 text-end">
            <button class="save-btn">
                Save Settings
            </button>
        </div>

    </form>

    <div class="backup-card mt-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            <div>
                <div class="backup-icon mb-3">
                    <i class="fas fa-database"></i>
                </div>
                <h5 class="fw-bold mb-1">Database Backup</h5>
                <p class="text-muted mb-0">
                    Run a fresh database backup and download the latest exported file.
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                <form method="POST" action="{{ route('admin.backup.run') }}">
                    @csrf
                    <button type="submit" class="btn btn-dark backup-btn">
                        <i class="fas fa-cloud-upload-alt me-1"></i>
                        Run Backup
                    </button>
                </form>

                @if($latestBackup)
                    <a href="{{ $latestBackup['url'] }}" target="_blank" class="btn btn-outline-primary backup-btn">
                        <i class="fas fa-download me-1"></i>
                        Download Latest
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-3 pt-3 border-top">
            @if($latestBackup)
                <div class="small text-muted">
                    Latest file: <span class="fw-semibold text-dark">{{ $latestBackup['name'] }}</span>
                    • {{ number_format($latestBackup['size'] / 1024, 1) }} KB
                    • {{ \Carbon\Carbon::createFromTimestamp($latestBackup['last_modified'])->diffForHumans() }}
                </div>
            @else
                <div class="small text-muted">
                    No database backup is available yet. Run a backup to generate one.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
