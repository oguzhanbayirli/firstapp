<x-profile :sharedData="$sharedData" :doctitle="$sharedData['username'] . ' - Posts'">
    @if($posts->count())
        <div class="list-group">
            @foreach($posts as $post)
                <x-post :post="$post" :hideAuthor="true" />
            @endforeach
        </div>
    @else
        <p class="text-center text-muted">Bu kullanıcının henüz gönderi yok.</p>
    @endif
</x-profile>