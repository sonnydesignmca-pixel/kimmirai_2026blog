<?php

use App\Models\Post;
use App\Livewire\Traits\UserTrait;
use App\Livewire\Traits\PostTrait;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use UserTrait;
    use PostTrait;
    public Post $post;

    public function deletePost(Post $post)
    {
        if (!empty($post->photo_path)) {
            Storage::disk("s3")->delete($post->photo_path);
        }
        $post->delete();
        Flux::toast("記事を削除しました", variant: "success");
        return $this->redirect(route("post.index"), navigate: true);
    }
};
?>

<div>
  <x-page-header>記事詳細</x-page-header>
  <x-panel>

    <div class="flex justify-between items-center-safe">
      <p class="p-4 text-lg font-semibold">{{ $this->post->title }}</p>
      <div class="flex gap-4">
        <div class="flex gap-2 items-center">
            <div>
                @auth
                  @if (!auth()->user()->bookmarks->contains($post->id))
                    <button title="記事をブックマーク" class="cursor-pointer" wire:click="bookmark({{ $post->id }})">
                      <flux:icon.bookmark variant="outline" size="12"></flux:icon.bookmark>
                    </button>
                  @else
                    <button title="ブックマークを解除" class="cursor-pointer" wire:click="unbookmark({{ $post->id }})">
                      <flux:icon.bookmark variant="solid" size="12" class="text-yellow-400"></flux:icon.bookmark>
                    </button>
                  @endif
                @endauth
            </div>

            <div class="text-sm">{{ count($post->bookmarked) }}</div>
        </div>
        <x-post-dropdown :$post />
      </div>

    </div>
    <hr class="w-full">
    <div class="prose leading-6 mt-4 p-4">{!! Str::markdown($this->post->body) !!}</div>

    @if (isset($post->photo_path))
      <div class="flex flex-wrap">
        @foreach ($post->photo_path as $path)
          <div x-data="{ open: false }">
            <button x-on:click="open = true" class="cursor-pointer hover:opacity-70">
              <img class="h-20 w-auto" src="{{ Storage::disk("s3")->url($path) }}" alt="">
            </button>

            <div x-cloak x-show="open"
              class="fixed top-0 left-0 w-full h-full bg-black/10 flex justify-center items-center border">
              <div x-on:click.away="open = false"
                class="ml-[20vw] max-w-[50vw] w-full h-auto flex flex-col bg-white rounded-xl px-4 py-2">
                <div class="text-end">
                  <div x-on:click="open = false" class="inline text-3xl cursor-pointer">x</div>
                </div>
                <img class="h-auto w-auto" src="{{ Storage::disk("s3")->url($path) }}" alt="">
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    <div class="flex items-center justify-end p-4 text-sm font-semibold">
      @if ($post->user->logo)
        <img src="{{ Storage::url($post->user->logo) }}" alt="" class="h-8 w-auto rounded-full mr-4">
      @endif
      <p><a href="{{ route("user.show", $post->user) }}" wire:navigate
          class="hover:text-blue-500">{{ $post->user->name }} </a>/
        {{ $post->created_at }}
      </p>
    </div>
    {{-- <x-like-button :$post /> --}}
  </x-panel>
</div>
