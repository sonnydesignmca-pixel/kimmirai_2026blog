<?php

use App\Models\User;
use App\Livewire\Traits\UserTrait;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    use UserTrait;
    public User $user;

    #[Computed]
    public function user()
    {
        return $this->user;
    }
};
?>

<div>
  <div class="mt-6 p-6 bg-white rounded-2xl shadow-md border border-gray-400 w-4/5 m-auto">
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        @if ($user->logo)
            <img src="{{ Storage::disk('s3')->url($user->logo) }}" alt="" class="h-12 w-auto rounded-full">
        @endif
        <p class="p-4 text-lg font-semibold">{{ $this->user->name }}</p>
      </div>

      <div>
        @can("notMyself", $user)
 @if (auth()->user()->followings->contains($user->id) && auth()->user()->followers->contains($user->id))
        <x-follow-badge />
        @endif

          @if (auth()->user()->followings->contains($user->id))
            <flux:button variant="danger" wire:click="unfollowFromUser({{ $user->id }})">フォロー解除</flux:button>
          @else
            <flux:button variant="primary" color="indigo" wire:click="followFromUser({{ $user->id }})">フォローする
            </flux:button>
          @endif
        @endcan

      </div>
    </div>
    <hr class="w-full">
    <a href="{{ route("user.posts", $user) }}" wire:navigate>
      <p class="mt-4 p-4 hover:text-blue-600">投稿記事 : {{ count($this->user->posts) }} 件</p>
    </a>
    <a href="{{ route('user.bookmarks',$user) }}" wire:navigate>
      <p class="mt-4 p-4 hover:text-blue-600">保存した記事 : {{ count($this->user->bookmarks) }} 件</p>
    </a>
    <div class="flex">
      <a href="{{ route("user.followings", $user) }}" wire:navigate>
        <p class="mt-4 p-4 hover:text-blue-600">フォロー : {{ count($this->user->followings) }} 人</p>
      </a>
      <a href="{{ route("user.followers", $user) }}" wire:navigate>
        <p class="mt-4 p-4 hover:text-blue-600">フォロワー : {{ count($this->user->followers) }} 人</p>
      </a>
    </div>
  </div>
</div>
