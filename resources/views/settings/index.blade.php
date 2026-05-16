@extends('layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')
@section('page-subtitle', 'Configure application-wide settings')

@section('content')

    <style>
        .settings-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #ddd;
        }

        .preview-box {
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- General Settings -->
                <div class="settings-card">
                    <h5 class="mb-3 fw-semibold">
                        <i class="bi bi-sliders2 me-2 text-primary"></i>General Settings
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Application Name</label>
                            <input type="text" name="app_name" class="form-control"
                                value="{{ $settings['app_name'] ?? 'CHAMS' }}">
                            <small class="text-muted">Displayed in browser title and sidebar</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Company Name</label>
                            <input type="text" name="company_name" class="form-control"
                                value="{{ $settings['company_name'] ?? 'Clinical Health Appointment Management System' }}">
                            <small class="text-muted">Displayed in sidebar footer</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Time Zone</label>
                            <select name="app_timezone" class="form-select">
                                <option value="Asia/Manila"
                                    {{ ($settings['app_timezone'] ?? '') == 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila
                                    (GMT+8)</option>
                                <option value="UTC" {{ ($settings['app_timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>
                                    UTC</option>
                                <option value="America/New_York"
                                    {{ ($settings['app_timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>
                                    America/New York</option>
                                <option value="Europe/London"
                                    {{ ($settings['app_timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>
                                    Europe/London</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Appearance Settings -->
                <div class="settings-card">
                    <h5 class="mb-3 fw-semibold">
                        <i class="bi bi-palette me-2 text-primary"></i>Appearance Settings
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="primary_color" class="form-control form-control-color"
                                    style="width: 60px;" value="{{ $settings['primary_color'] ?? '#8b5a8f' }}">
                                <input type="text" class="form-control"
                                    value="{{ $settings['primary_color'] ?? '#8b5a8f' }}" id="primary_color_text" readonly>
                                <div class="color-preview"
                                    style="background-color: {{ $settings['primary_color'] ?? '#8b5a8f' }}"></div>
                            </div>
                            <small class="text-muted">Changes buttons, active menu items, pagination, and accent
                                colors</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Sidebar Color</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="sidebar_color" class="form-control form-control-color"
                                    style="width: 60px;" value="{{ $settings['sidebar_color'] ?? '#2a1a2e' }}">
                                <input type="text" class="form-control"
                                    value="{{ $settings['sidebar_color'] ?? '#2a1a2e' }}" id="sidebar_color_text" readonly>
                                <div class="color-preview"
                                    style="background-color: {{ $settings['sidebar_color'] ?? '#2a1a2e' }}"></div>
                            </div>
                            <small class="text-muted">Changes the sidebar background gradient</small>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="mt-3 p-3 rounded" style="background-color: #f5f0f7;">
                        <label class="form-label fw-semibold mb-2">Live Preview</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-primary"
                                style="background-color: {{ $settings['primary_color'] ?? '#8b5a8f' }}; border-color: {{ $settings['primary_color'] ?? '#8b5a8f' }}">
                                <i class="bi bi-check me-1"></i>Primary Button
                            </button>
                            <button class="btn btn-outline-primary"
                                style="color: {{ $settings['primary_color'] ?? '#8b5a8f' }}; border-color: {{ $settings['primary_color'] ?? '#8b5a8f' }}">
                                Outline Button
                            </button>
                            <div class="p-2 rounded"
                                style="background: {{ $settings['sidebar_color'] ?? '#2a1a2e' }}; color: white;">
                                <i class="bi bi-house me-1"></i> Sidebar Preview
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours Settings -->
                <div class="settings-card">
                    <h5 class="mb-3 fw-semibold">
                        <i class="bi bi-clock-history me-2 text-primary"></i>Business Hours
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Start Time</label>
                            <input type="time" name="business_start_time" class="form-control"
                                value="{{ $settings['business_start_time'] ?? '09:00' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">End Time</label>
                            <input type="time" name="business_end_time" class="form-control"
                                value="{{ $settings['business_end_time'] ?? '17:00' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Appointment Duration (minutes)</label>
                            <input type="number" name="appointment_duration" class="form-control"
                                value="{{ $settings['appointment_duration'] ?? '30' }}" step="15" min="15">
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="settings-card">
                    <h5 class="mb-3 fw-semibold">
                        <i class="bi bi-bell me-2 text-primary"></i>Notification Settings
                    </h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input type="hidden" name="enable_email_notifications" value="0">
                                <input type="checkbox" name="enable_email_notifications" class="form-check-input"
                                    value="1" id="enable_email"
                                    {{ $settings['enable_email_notifications'] ?? false ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="enable_email">
                                    Enable Email Notifications
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Reminder Hours Before</label>
                            <input type="number" name="reminder_hours_before" class="form-control"
                                value="{{ $settings['reminder_hours_before'] ?? '24' }}" min="1">
                            <small class="text-muted">Hours before appointment to send reminder</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Admin Notification Email</label>
                            <input type="email" name="admin_notification_email" class="form-control"
                                value="{{ $settings['admin_notification_email'] ?? 'admin@chams.com' }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Save All Settings
                    </button>
                    <a href="{{ route('admin.settings.reset') }}" class="btn btn-outline-danger"
                        onclick="return confirm('Reset all settings to default values? This cannot be undone.')">
                        <i class="bi bi-arrow-repeat me-1"></i>Reset to Default
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Update text inputs and preview when color picker changes
        const primaryColor = document.querySelector('[name="primary_color"]');
        const sidebarColor = document.querySelector('[name="sidebar_color"]');

        if (primaryColor) {
            primaryColor.addEventListener('input', function() {
                document.getElementById('primary_color_text').value = this.value;
                // Update live preview
                const previewBtn = document.querySelector('.btn-primary');
                const outlineBtn = document.querySelector('.btn-outline-primary');
                if (previewBtn) {
                    previewBtn.style.backgroundColor = this.value;
                    previewBtn.style.borderColor = this.value;
                }
                if (outlineBtn) {
                    outlineBtn.style.color = this.value;
                    outlineBtn.style.borderColor = this.value;
                }
            });
        }

        if (sidebarColor) {
            sidebarColor.addEventListener('input', function() {
                document.getElementById('sidebar_color_text').value = this.value;
                // Update live preview
                const sidebarPreview = document.querySelector('.p-2.rounded');
                if (sidebarPreview) sidebarPreview.style.background = this.value;
            });
        }
    </script>
@endpush
