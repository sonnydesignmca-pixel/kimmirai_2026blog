<?php

namespace App\Livewire\Traits;

use App\Models\Post;
use App\Models\User;
use Flux\Flux;

trait UserTrait
{
    public function followFromPost(Post $post)
    {
        auth()->user()->followings()->syncWithoutDetaching([$post->user_id]);
        Flux::toast('フォローしました', variant: 'success');
    }

    public function unfollowFromPost(Post $post)
    {
        auth()->user()->followings()->detach($post->user_id);
        Flux::toast('フォローを解除しました', variant: 'success');
    }

    public function followFromUser(User $user)
    {
        auth()->user()->followings()->syncWithoutDetaching([$user->id]);
        Flux::toast('フォローしました', variant: 'success');
    }

    public function unfollowFromUser(User $user)
    {
        auth()->user()->followings()->detach($user->id);
        Flux::toast('フォローを解除しました', variant: 'success');
    }


}
