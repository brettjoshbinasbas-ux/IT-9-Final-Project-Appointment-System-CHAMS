@extends('layouts.app')

@section('title', 'Deleted Clients')
@section('page-title', 'Deleted Clients')
@section('page-subtitle', 'Restore or permanently delete archived clients')

@section('page-actions')
    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Active Clients
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
                        <th>Phone</th>
                        <th>Deleted At</th>
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
                            <td>{{ $client->deleted_at->format('M d, Y H:i') }}</td>
                            <td>
                                <form action="{{ route('clients.restore', $client->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-arrow-repeat"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('clients.force-delete', $client->id) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Permanently delete {{ $client->full_name }}? This cannot be undone.')">
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
                                No deleted clients found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $clients->links() }}
        </div>
    </div>
@endsection
