<x-layout doctitle="Manage Avatar">
    <div class="container py-md-4 container--narrow">
        <div class="mb-5">
            <h3 class="mb-1"><i class="fas fa-camera text-danger"></i> Upload Profile Picture</h3>
            <p class="text-muted small">Update your profile avatar. JPG, PNG, GIF • Max 5MB</p>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center py-5">
                <img id="preview" src="{{ auth()->user()->avatar }}" class="rounded-circle border avatar-preview">
            </div>
        </div>

        <livewire:avatar-upload />
    </div>

</x-layout>