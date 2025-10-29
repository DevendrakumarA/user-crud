@extends('layouts.app')

@section('toastr')
    @if(session('success'))
        <script>toastr.success("{{ session('success') }}");</script>
    @endif
    @if(session('error'))
        <script>toastr.error("{{ session('error') }}");</script>
    @endif
    @if(session('warning'))
        <script>toastr.warning("{{ session('warning') }}");</script>
    @endif
    @if(session('info'))
        <script>toastr.info("{{ session('info') }}");</script>
    @endif
@endsection

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
                        <a href="{{ route('admins.export.csv') }}" class="btn btn-success export-btn" data-filename="users.csv">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                        <a href="{{ route('admins.export.pdf') }}" class="btn btn-danger export-btn" data-filename="users.pdf">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ $search }}" placeholder="Search by name or email...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
                <div class="col-md-2 ms-auto">
                    <div class="input-group">
                        <label class="input-group-text" for="per_page">Per page</label>
                        <select id="per_page" name="per_page" class="form-select" onchange="this.form.submit()">
                            @foreach([5,10,25,50,100] as $pp)
                                <option value="{{ $pp }}" {{ request('per_page', 5) == $pp ? 'selected' : '' }}>{{ $pp }}</option>
                            @endforeach
                        </select>
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

            <div class="d-flex justify-content-center mt-4 mx-1">
                {{ $admins->appends(['search' => $search, 'sort' => $sort, 'per_page' => request('per_page', 5)])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const exportButtons = document.querySelectorAll('.export-btn');

    exportButtons.forEach(btn => {
        btn.addEventListener('click', async function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            const fallbackName = this.dataset.filename || 'download';

            // Show immediate toastr
            toastr.info('Preparing your download...');

            try {
                const response = await fetch(url, { method: 'GET', credentials: 'same-origin' });
                if (!response.ok) throw new Error('Network response was not ok');

                const blob = await response.blob();

                // Try to get filename from Content-Disposition header
                let filename = fallbackName;
                const cd = response.headers.get('content-disposition');
                if (cd) {
                    const match = cd.match(/filename\*=UTF-8''([^;\n]+)/) || cd.match(/filename="?([^";\n]+)"?/);
                    if (match) filename = decodeURIComponent(match[1]);
                }

                const blobUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = blobUrl;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(blobUrl);

                toastr.success('Download started');
            } catch (err) {
                console.error(err);
                toastr.error('Failed to download file');
            }
        });
    });
});
</script>
@endpush
