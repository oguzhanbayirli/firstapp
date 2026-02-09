<x-layout :doctitle="'Edit Post: ' . $post->title">
  <div class="container py-md-5 container--narrow">
      <p class="mb-2"><small><a href="/post/{{ $post->id }}" class="text-muted"><i class="fas fa-arrow-left"></i> Back to post</a></small></p>
      <h3 class="mb-3"><i class="fas fa-edit text-danger"></i> Edit Post</h3>
      <livewire:edit-post :post="$post" />
    </div>
</x-layout>