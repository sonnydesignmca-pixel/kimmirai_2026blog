<?php

namespace App\Livewire\Forms;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;

use Livewire\Form;

class PostForm extends Form
{
    public ?Post $post;

    #[Locked]
    public $id;

    #[Validate('required')]
    public $title = '';

    #[Validate('required')]
    public $body = '';

    #[Validate([
        'photos' => 'nullable|array', // 配列自体が空（未選択）でもOKにする
        'photos.*' => 'mimes:jpeg,png,jpg,gif,svg|max:1024', // 各ファイルへのルール
    ])]
    public $photos = [];

    public $photo_path = [];

    // public $published = false;
    // public $notifications = [];
    // public $allowNotifications = false;
    public function setPost(Post $post)
    {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->body = $post->body;
        // $this->published = $post->published;
        // $this->notifications = $post->notifications ?? [];
        // notificationに何か値が入っていればtrue Yesを選択
        // $this->allowNotifications = count($this->notifications) > 0;
        $this->photo_path = $post->photo_path;

        // updateメソッドで使えるように$post本体を定義
        $this->post = $post;
    }
    public function store()
    {
        $this->validate();
        if ($this->photos) {
            foreach($this->photos as $photo)
            $this->photo_path[] = $photo->storePublicly('post_photos', ['disk' => 'public']);
        }
        auth()->user()->posts()->create(
            $this->only([
                'title',
                'body',
                'photo_path'
            ])
        );

        // cache()->forget('published-count');

    }

    public function update()
    {
        $this->validate();

        if ($this->photos) {
            foreach ($this->photos as $photo)
                $this->photo_path[] = $photo->storePublicly('post_photos', ['disk' => 'public']);
        }
        $this->post->update(
            $this->only([
                'title',
                'body',
                'photo_path'
            ]),
        );

        // cache()->forget('published-count');

    }

    public function deleteSavedPhoto($i)
    {
        unset($this->photo_path[$i]);
        $this->photo_path = array_values($this->photo_path);
    }

    public function deleteCurrentPhoto($i)
    {
        unset($this->photos[$i]);
        $this->photos = array_values($this->photos);
    }

}
