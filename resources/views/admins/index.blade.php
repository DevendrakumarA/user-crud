@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0 text-primary">Admin Management</h3>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admins.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Users
                    </a>
                    <div class="btn-group ms-2">
                        <a href="{{ route('admins.export.csv') }}" class="btn btn-success">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                        <a href="{{ route('admins.export.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search by name or email...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>
                    <a href="?sort=name&search={{ $search }}" class="text-decoration-none text-dark">
                        Name <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th>
                    <a href="?sort=email&search={{ $search }}" class="text-decoration-none text-dark">
                        Email <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th>
                    <a href="?sort=phone&search={{ $search }}" class="text-decoration-none text-dark">
                        Phone <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th class="text-center">Profile</th>
                <th class="text-center">Resume</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @foreach ($admins as $user)
        <tr>
            <td>
                <div class="fw-medium">{{ $user->name }}</div>
            </td>
            <td>
                <div class="text-muted">
                    <i class="fas fa-envelope text-secondary me-1"></i>{{ $user->email }}
                </div>
            </td>
            <td>
                <div class="text-muted">
                    <i class="fas fa-phone text-secondary me-1"></i>{{ $user->phone ?: 'N/A' }}
                </div>
            </td>
            <td class="text-center">
                @if($user->profile)
                    <a href="{{ asset('storage/'.$user->profile) }}" data-lightbox="profile-{{ $user->id }}" data-title="{{ $user->name }}'s Profile Picture">
                        <img src="{{ asset('storage/'.$user->profile) }}" width="50" height="50" class="img-fluid border rounded" alt="{{ $user->name }}'s Profile">
                    </a>
                    <!-- <div class="mt-1">
                        <small class="text-muted">{{ basename($user->profile) }}</small>
                    </div> -->
                @else
                    <span class="text-muted">No Image</span>
                @endif
            </td>
            <td class="text-center">
                @if($user->resume)
                    <div class="d-flex flex-column align-items-center">
                        <div class="btn-group mb-2">
                            <a href="{{ asset('storage/'.$user->resume) }}" class="btn btn-sm btn-info" target="_blank">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ asset('storage/'.$user->resume) }}" class="btn btn-sm btn-success" download>
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                        <!-- <small class="text-muted">{{ basename($user->resume) }}</small> -->
                    </div>
                @else
                    <span class="text-muted">No Resume</span>
                @endif
            </td>
            <td class="text-center">
                <div class="btn-group">
                    <a href="{{ route('admins.edit', $user->id) }}" class="btn btn-warning btn-sm" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('admins.delete', $user->id) }}" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Are you sure you want to delete this admin?')"
                       title="Delete">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
        @endforeach
    </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $admins->appends(['search' => $search, 'sort' => $sort])->links() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
