<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input 
                type="text" 
                class="form-control @error('title') is-invalid @enderror"
                id="title"
                wire:model="title"
                placeholder="Post title..."
            >
            @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="body" class="form-label">Content</label>
            <textarea 
                class="form-control @error('body') is-invalid @enderror"
                id="body"
                wire:model="body"
                rows="8"
                placeholder="Write your post content..."
            ></textarea>
            @error('body')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        @if($errorMessage)
            <div class="alert alert-danger" role="alert">{{ $errorMessage }}</div>
        @endif

        <div class="d-flex gap-2">
            <button 
                type="submit" 
                class="btn btn-primary px-3"
                wire:loading.attr="disabled"
                {{ $isLoading ? 'disabled' : '' }}
            >
                @if($isLoading)
                    <i class="fas fa-spinner fa-spin"></i> Creating...
                @else
                    <i class="fas fa-paper-plane"></i> Create Post
                @endif
            </button>
            <a href="/" class="btn btn-outline-secondary px-3">Cancel</a>
        </div>
    </form>
</div>
