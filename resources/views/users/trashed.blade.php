@extends('layouts.app')

@section('title', 'Deactivated Users')
@section('page-title', 'Deactivated Users')
@section('page-subtitle', 'Restore or permanently delete deactivated accounts')

@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Active Users
    </a>
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Deactivated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span
                                    class="badge {{ $user->isAdmin() ? 'bg-danger' : ($user->isStaff() ? 'bg-primary' : 'bg-info') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->deleted_at->format('M d, Y H:i') }}</td>
                            <td>
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-arrow-repeat"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('users.force-delete', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone if they have records.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Permanent Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No deactivated users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
