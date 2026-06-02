<?php

namespace App\Livewire\Traits;
use App\Models\Post;
use Flux\Flux;
use Illuminate\Support\Facades\Storage;

trait PostTrait
{
    public function deletePost(Post $post)
    {
        if(!empty($post->photo_path)){
            Storage::disk('s3')->delete($post->photo_path);
        }
        $post->delete();
        Flux::toast('記事を削除しました', variant: 'success');
    }

    public function bookmark(Post $post)
    {
        auth()->user()->bookmarks()->syncWithoutDetaching([$post->id]);
        Flux::toast('記事をブックマークしました', variant: 'success');
    }

    public function unbookmark(Post $post)
    {
        auth()->user()->bookmarks()->detach([$post->id]);
        Flux::toast('ブックマークを解除しました', variant: 'success');
    }

}
