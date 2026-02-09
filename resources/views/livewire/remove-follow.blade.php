<div>
    <button 
        class="btn btn-outline-secondary btn-sm" 
        wire:click="unfollow"
        wire:loading.attr="disabled"
        wire:target="unfollow"
        {{ $isLoading ? 'disabled' : '' }}
    >
        <span wire:loading.remove wire:target="unfollow"><i class="fas fa-user-times"></i> Unfollow</span>
        <span wire:loading wire:target="unfollow"><i class="fas fa-spinner fa-spin"></i> Unfollowing...</span>
    </button>

    @if($errorMessage)
        <div class="alert alert-danger mt-2 mb-0 small">{{ $errorMessage }}</div>
    @endif
</div>
