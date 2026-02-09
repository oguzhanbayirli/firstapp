<div class="text-center p-5">
    <h1>Livewire Test Component</h1>
    <div class="my-4">
        <h2 class="display-1">{{ $count }}</h2>
    </div>
    <div class="d-flex gap-2 justify-content-center">
        <button wire:click="decrement" class="btn btn-danger btn-lg">-</button>
        <button wire:click="increment" class="btn btn-success btn-lg">+</button>
    </div>
    <p class="mt-4 text-muted">
        @if($count === 0)
            Başlamak için butona tıklayın!
        @elseif($count > 0)
            <i class="fas fa-check-circle text-success"></i> Livewire çalışıyor! ✓
        @else
            <i class="fas fa-check-circle text-success"></i> Livewire çalışıyor! ✓
        @endif
    </p>
</div>
