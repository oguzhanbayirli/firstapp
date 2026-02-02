<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function showCreateForm() {
        return view('create-post');
    }
    public function showSinglePost(Post $post) {
        $post->body = strip_tags(Str::markdown($post->body), '<p><a><strong><em><ul><ol><li><br>');
        return view('single-post', ['post' => $post]);
    }
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required|min:3|max:100',
            'body' => 'required|min:10|max:1000',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = Auth::id();
        $newPost = Post::create($incomingFields);
        
        return redirect("/post/{$newPost->id}")->with('success', 'Your post has been created.');
    }
    public function delete(Post $post) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect('/')->with('failure', 'User not authenticated.');
        }
        if ($user->cannot('delete', $post)) {
            return redirect("/post/{$post->id}")->with('failure', 'You do not have permission to delete this post.');
        }
        $post->delete();
        return redirect("/profile/" . $user->username)->with('success', 'Post successfully deleted.');
    }
    public function showEditForm(Post $post) {
        return view('edit-post', ['post' => $post]);
    }
    public function update(Post $post, Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect('/')->with('failure', 'User not authenticated.');
        }
        if ($user->cannot('update', $post)) {
            return redirect("/post/{$post->id}")->with('failure', 'You do not have permission to edit this post.');
        }
        $incomingFields = $request->validate([
            'title' => 'required|min:3|max:100',
            'body' => 'required|min:10|max:1000',
        ]);
        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $post->update($incomingFields);
        return back()->with('success', 'Post successfully updated.');
    }
    public function search(Request $request) {
        $posts = Post::search($request->input('query'))->with('user')->paginate(10)->withQueryString();
        $posts->load('user');
        return view('search-results', ['posts' => $posts, 'query' => $request->input('query')]);
    }

}
