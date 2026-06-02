<?php

use App\Models\User;
use App\Livewire\Traits\UserTrait;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Component;

new class extends Component {
    use UserTrait;
    use WithPagination;

    public User $user;

    #[Computed]
    public function followings()
    {
        return $this->user->followings;
    }
};
?>

<div>
  <x-page-header>フォローしているユーザー</x-page-header>
  @foreach ($this->followings as $following)
    <x-panel class="flex justify-between">
      <div class="flex items-center">
        @if ($following->logo)
            <img src="{{ Storage::disk('s3')->url($following->logo) }}" alt="" class="h-8 w-auto rounded-full mr-4">
        @endif
        <p class="font-semibold"><a href="{{ route("user.show", $following) }}" wire:navigate
            class="hover:text-blue-500">{{ $following->name }} </a></p>
        </p>
      </div>
      <div>
        @can("notMyself", $following)
          @if (auth()->user()->followings->contains($following->id) && auth()->user()->followers->contains($following->id))
            <x-follow-badge />
          @endif

          @if (auth()->user()->followings->contains($following->id))
            <flux:button variant="danger" wire:click="unfollowFromUser({{ $following->id }})">フォロー解除</flux:button>
          @else
            <flux:button variant="primary" color="indigo" wire:click="followFromUser({{ $following->id }})">フォローする
            </flux:button>
          @endif
        @endcan

      </div>
    </x-panel>
  @endforeach
  {{-- @dump($this->followings) --}}
</div>
