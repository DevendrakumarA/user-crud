@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Add Admin</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admins.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter full name" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g., +1 555-555-5555" class="form-control @error('phone') is-invalid @enderror">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profile_pic" class="form-label">Profile Picture</label>
                            <input type="file" id="profile_pic" name="profile_pic" class="form-control @error('profile_pic') is-invalid @enderror" accept=".jpeg,.png,.gif">
                            <div class="form-text">Allowed: image files (jpeg, png, gif). Max recommended size: 2MB.</div>
                            @error('profile_pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="profilePreview" src="#" alt="Profile preview" style="display:none; max-width:120px; max-height:120px; border-radius:6px;" class="border" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="resume" class="form-label">Resume</label>
                            <input type="file" id="resume" name="resume" class="form-control @error('resume') is-invalid @enderror" accept=".pdf,.doc,.docx">
                            <div class="form-text">Allowed: PDF, DOC, DOCX.</div>
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <small id="resumeFilename" class="text-muted"></small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Save
                            </button>
                            <a href="{{ route('admins.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileInput = document.getElementById('profile_pic');
    const profilePreview = document.getElementById('profilePreview');
    const resumeInput = document.getElementById('resume');
    const resumeFilename = document.getElementById('resumeFilename');

    if (profileInput) {
        profileInput.addEventListener('change', function (e) {
            const file = this.files[0];
            if (!file) return;
            // size check ~2MB
            const maxImageSize = 5 * 1024 * 1024;
            if (file.size > maxImageSize) {
                toastr.error('Profile image exceeds 2MB. Please choose a smaller file.');
                this.value = '';
                profilePreview.style.display = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (evt) {
                profilePreview.src = evt.target.result;
                profilePreview.style.display = 'inline-block';
            }
            reader.readAsDataURL(file);
        });
    }

    if (resumeInput) {
        resumeInput.addEventListener('change', function (e) {
            const file = this.files[0];
            if (!file) {
                resumeFilename.textContent = '';
                return;
            }
            // size check ~5MB
            const maxResumeSize = 5 * 1024 * 1024;
            if (file.size > maxResumeSize) {
                toastr.error('Resume exceeds 5MB. Please choose a smaller file.');
                this.value = '';
                resumeFilename.textContent = '';
                return;
            }
            resumeFilename.textContent = file.name;
        });
    }
});
</script>
@endpush