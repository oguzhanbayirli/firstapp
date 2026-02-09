<div>
    <form wire:submit.prevent="upload">
        <div class="mb-3">
            <label for="avatar" class="form-label">Upload Avatar</label>
            <input 
                type="file" 
                class="form-control @error('avatar') is-invalid @enderror"
                id="avatar"
                wire:model="avatar"
                accept="image/*"
            >
            @error('avatar')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        @if($avatar)
            <div class="mb-3">
                <p class="text-muted small">Preview:</p>
                <img src="{{ $avatar->temporaryUrl() }}" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
            </div>
        @endif

        @if($successMessage)
            <div class="alert alert-success" role="alert">{{ $successMessage }}</div>
        @endif

        @if($errorMessage)
            <div class="alert alert-danger" role="alert">{{ $errorMessage }}</div>
        @endif

        <button 
            type="submit" 
            class="btn btn-primary"
            wire:loading.attr="disabled"
            {{ $isLoading ? 'disabled' : '' }}
        >
            @if($isLoading)
                <i class="fas fa-spinner fa-spin"></i> Uploading...
            @else
                <i class="fas fa-upload"></i> Upload Avatar
            @endif
        </button>
    </form>
</div>
