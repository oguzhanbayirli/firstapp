<div>
    <button 
        class="btn btn-primary btn-sm" 
        wire:click="follow"
        wire:loading.attr="disabled"
        wire:target="follow"
        {{ $isLoading ? 'disabled' : '' }}
    >
        <span wire:loading.remove wire:target="follow"><i class="fas fa-user-plus"></i> Follow</span>
        <span wire:loading wire:target="follow"><i class="fas fa-spinner fa-spin"></i> Following...</span>
    </button>

    @if($errorMessage)
        <div class="alert alert-danger mt-2 mb-0 small">{{ $errorMessage }}</div>
    @endif
</div>
