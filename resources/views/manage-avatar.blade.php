<x-layout>
    <div class="container py-md-5 container--narrow">
        <div class="text-center mb-4">
            <img id="preview" src="{{ auth()->user()->avatar }}" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
        </div>

        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="avatar" class="text-muted mb-2"><small>Choose Avatar</small></label>
                <div class="position-relative">
                    <input type="file" name="avatar" id="avatar" class="d-none @error('avatar') is-invalid @enderror" accept="image/*" required />
                    <label for="avatar" class="btn btn-outline-secondary w-100 text-left d-flex align-items-center justify-content-between" style="cursor: pointer; padding: 0.65rem 1rem; border: 2px dashed #dee2e6; background-color: #f8f9fa;">
                        <span id="file-label" class="text-muted">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>
                            Click to select image
                        </span>
                        <i class="fas fa-image text-muted"></i>
                    </label>
                </div>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle"></i> JPG, PNG, GIF • Max 5MB
                </small>
                @error('avatar')
                    <p class="alert alert-danger alert-sm small mt-2 mb-0">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-sm flex-grow-1" style="padding: 0.45rem 1rem; font-weight: 600; background-color: #f9322c; border-color: #f9322c; color: #fff;">
                    <i class="fas fa-check-circle"></i> Save Avatar
                </button>
                <a href="/profile/{{ auth()->user()->username }}" class="btn btn-sm" style="padding: 0.45rem 1rem; font-weight: 600; color: #f9322c; border: 1px solid #f9322c;">
                    <i class="fas fa-arrow-left"></i> Back
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
                
                // Update label with file name
                document.getElementById('file-label').innerHTML = `<i class="fas fa-check-circle mr-2 text-success"></i>${file.name}`;
            }
        });
    </script>
</x-layout>