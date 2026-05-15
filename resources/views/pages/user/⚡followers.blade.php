<?php

use App\Models\User;
use App\Livewire\Traits\UserTrait;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use UserTrait;
    use WithPagination;

    public User $user;

    #[Computed]
    public function followers()
    {
        return $this->user->followers;
    }
};
?>

<div>
  <x-page-header>フォローされているユーザー</x-page-header>
  @foreach ($this->followers as $follower)
    <x-panel class="flex justify-between">
      <div class="flex items-center">
        <img src="{{ Storage::url($follower->logo) }}" alt="" class="h-8 w-auto rounded-full">
        <p class="font-semibold"><a href="{{ route("user.show", $follower) }}" wire:navigate
            class="hover:text-blue-500">{{ $follower->name }} </a></p>
        </p>
      </div>
      <div>
        @can("notMyself", $follower)

        @if (auth()->user()->followings->contains($follower->id) && auth()->user()->followers->contains($follower->id))
        <x-follow-badge />
        @endif

          @if (auth()->user()->followings->contains($follower->id))
            <flux:button variant="danger" wire:click="unfollowFromUser({{ $follower->id }})">フォロー解除</flux:button>
          @else
            <flux:button variant="primary" color="indigo" wire:click="followFromUser({{ $follower->id }})">フォローする
            </flux:button>
          @endif

        @endcan

      </div>
    </x-panel>
  @endforeach
  {{-- @dump($this->followings) --}}
</div>
