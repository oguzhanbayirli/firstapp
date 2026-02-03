<x-layout doctitle="Manage Avatar">
    <div class="container py-md-4 container--narrow">
        <div class="mb-5">
            <h3 class="mb-1"><i class="fas fa-camera text-danger"></i> Upload Profile Picture</h3>
            <p class="text-muted small">Update your profile avatar. JPG, PNG, GIF • Max 5MB</p>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-5">
                <img id="preview" src="{{ auth()->user()->avatar }}" class="rounded-circle border" style="width: 120px; height: 120px; object-fit: cover; border-width: 3px !important; border-color: #f9322c !important;">
            </div>
        </div>

        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="avatar" class="text-muted mb-2 font-weight-bold"><small>Choose Image</small></label>
                <div class="position-relative">
                    <input type="file" name="avatar" id="avatar" class="d-none @error('avatar') is-invalid @enderror" accept="image/*" required />
                    <label for="avatar" class="custom-file-upload">
                        <div class="d-flex align-items-center justify-content-center" style="min-height: 120px;">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt mb-3" style="font-size: 2rem; color: #f9322c;"></i>
                                <p class="mb-1 text-muted"><strong>Click to upload</strong> or drag and drop</p>
                                <span id="file-label" class="text-muted small">PNG, JPG, GIF up to 5MB</span>
                            </div>
                        </div>
                    </label>
                </div>
                @error('avatar')
                    <div class="alert alert-danger small mt-2 mb-0">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-3" style="font-weight: 600;">
                    <i class="fas fa-check-circle mr-2"></i> Save Avatar
                </button>
                <a href="/profile/{{ auth()->user()->username }}" class="btn btn-outline-secondary px-3" style="font-weight: 600;">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Profile
                </a>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('avatar').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById('preview').src = event.target.result;
                };
                reader.readAsDataURL(file);
                
                document.getElementById('file-label').innerHTML = `<i class="fas fa-check-circle mr-1 text-success"></i>Selected: ${file.name}`;
            }
        });
    </script>

    <style>
        .custom-file-upload {
            cursor: pointer;
            display: block;
            padding: 20px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: var(--transition);
        }

        .custom-file-upload:hover {
            border-color: #f9322c;
            background-color: rgba(249, 50, 44, 0.05);
        }
    </style>
</x-layout>