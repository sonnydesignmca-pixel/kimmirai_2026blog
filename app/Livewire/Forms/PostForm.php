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
        'photos' => 'nullable|array',
        'photos.*' => 'mimes:jpeg,png,jpg,gif,svg|max:1024',
    ])]
    public $photos = [];

    public $photo_path = [];
    public function setPost(Post $post)
    {
        $this->id = $post->id;
        $this->title = $post->title;
        $this->body = $post->body;
        $this->photo_path = $post->photo_path;
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
