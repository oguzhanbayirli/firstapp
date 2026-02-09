<div>
    @if(!$showConfirmation)
        <button 
            class="btn btn-outline-danger btn-sm" 
            wire:click="toggleConfirmation"
        >
            <i class="fas fa-trash"></i> Delete
        </button>
    @else
        <div class="alert alert-warning" role="alert">
            <p class="mb-3">Are you sure you want to delete this post? This action cannot be undone.</p>
            
            @if($errorMessage)
                <div class="alert alert-danger mb-3">{{ $errorMessage }}</div>
            @endif

            <div class="d-flex gap-2">
                <button 
                    class="btn btn-danger btn-sm"
                    wire:click="delete"
                    wire:loading.attr="disabled"
                    {{ $isLoading ? 'disabled' : '' }}
                >
                    @if($isLoading)
                        <i class="fas fa-spinner fa-spin"></i> Deleting...
                    @else
                        <i class="fas fa-check"></i> Yes, Delete
                    @endif
                </button>
                <button 
                    class="btn btn-secondary btn-sm"
                    wire:click="toggleConfirmation"
                    {{ $isLoading ? 'disabled' : '' }}
                >
                    Cancel
                </button>
            </div>
        </div>
    @endif
</div>
