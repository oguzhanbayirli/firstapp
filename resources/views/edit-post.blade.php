<x-layout :doctitle="'Edit Post: ' . $post->title">
  <div class="container py-md-5 container--narrow">
      <p class="mb-2"><small><a href="/post/{{ $post->id }}" class="text-muted"><i class="fas fa-arrow-left"></i> Back to post</a></small></p>
      <h3 class="mb-3"><i class="fas fa-edit text-danger"></i> Edit Post</h3>
      <form action="/post/{{ $post->id }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="post-title" class="text-muted mb-1 font-weight-bold"><small>Title</small></label>
          <input 
            required 
            value="{{ old('title', $post->title) }}" 
            name="title" 
            id="post-title" 
            class="form-control form-control-title border-0 shadow-sm" 
            type="text" 
            placeholder="Enter title..." 
            autocomplete="off" 
            style="padding: 8px 12px; background-color: #f8f9fa; font-size: 1.1rem;"
          />
          @error('title')
            <p class="alert alert-danger small mt-2 mb-0">{{ $message }}</p>
          @enderror
        </div>

        <div class="form-group mt-3">
          <label for="post-body" class="text-muted mb-1 font-weight-bold"><small>Content</small></label>
          <textarea 
            required 
            name="body" 
            id="post-body" 
            class="body-content tall-textarea form-control border-0 shadow-sm" 
            placeholder="Write your thoughts here..."
            style="padding: 8px 12px; background-color: #f8f9fa; resize: vertical; min-height: 200px;"
          >{{ old('body', $post->body) }}</textarea>
          @error('body')
            <p class="alert alert-danger small mt-2 mb-0">{{ $message }}</p>
          @enderror
        </div>

        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-sm px-3" style="background-color: #f9322c; border-color: #f9322c; color: #fff;">
            <i class="fas fa-save mr-1"></i> Save
          </button>
          <a href="/post/{{ $post->id }}" class="btn btn-sm btn-outline-secondary px-3">
            <i class="fas fa-times mr-1"></i> Cancel
          </a>
        </div>
      </form>
    </div>
</x-layout>