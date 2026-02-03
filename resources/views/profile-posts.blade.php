<x-profile :avatar="$avatar" :username="$username" :postCount="$postCount" :currentlyFollowing="$currentlyFollowing" :followerCount="$followerCount" :followingCount="$followingCount" :doctitle="$username . ' - Posts'">
    @if($posts->count())
        <div class="list-group">
            @foreach($posts as $post)
                <x-post :post="$post" :hideAuthor="true" />
            @endforeach
        </div>
    @else
        <div class="empty-state-container">
            <div class="empty-state-icon">
                <i class="fas fa-pencil-alt"></i>
            </div>
            <h3 class="empty-state-title">No Posts Yet</h3>
            <p class="empty-state-text">{{ $username }} hasn't shared any posts yet.</p>
        </div>
    @endif
</x-profile>