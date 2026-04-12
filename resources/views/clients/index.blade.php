@extends('layouts.app')

@section('title', 'Clients')
@section('page-title', 'Clients')
@section('page-subtitle', 'Manage registered clients')

@section('page-actions')
    @if (!auth()->user()->isStaff())
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Add Client
        </a>
    @endif
@endsection

@section('content')

    {{-- Search bar --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}" class="d-flex gap-2">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email, or phone..."
                    value="{{ $search }}">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i>
                </button>
                @if ($search)
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Clients table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td class="fw-semibold">{{ $client->full_name }}</td>
                            <td>{{ $client->email ?? '—' }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (!auth()->user()->isStaff())
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Delete {{ $client->full_name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No clients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $clients->withQueryString()->links() }}
        </div>

    @endsection
