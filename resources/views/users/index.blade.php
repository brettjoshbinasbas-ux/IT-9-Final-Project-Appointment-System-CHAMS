@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Admin only — manage system accounts')

@section('page-actions')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>Add User
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
                        <th>Created</th>
                        <th>Status</th>
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
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                @if ($user->isActive())
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Deactivated</span>
                                @endif
                            </td>
                            <td>
                                @if ($user->id !== auth()->id())
                                    @if ($user->isActive())
                                        <!-- Add Edit Button -->
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Deactivate {{ $user->name }}? They can be restored later.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-person-x"></i> Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.restore', $user->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-arrow-repeat"></i> Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('users.force-delete', $user->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Permanent
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-muted small">Current User</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
