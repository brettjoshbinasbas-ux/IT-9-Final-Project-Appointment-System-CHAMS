@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update user account information')

@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Users
    </a>
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-fluid">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning fw-semibold">
                    <i class="bi bi-pencil me-2 text-white"></i>Edit User: {{ $user->name }}
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" placeholder="Full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Role <span class="text-danger">*</span>
                            </label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="">-- Select Role --</option>
                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>
                                    Staff
                                </option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="receptionist"
                                    {{ old('role', $user->role) === 'receptionist' ? 'selected' : '' }}>
                                    Receptionist
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Password <span class="text-muted">(Leave blank to keep current)</span>
                            </label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Minimum 8 characters">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only fill if you want to change the password.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Repeat password">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i>Update User
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
