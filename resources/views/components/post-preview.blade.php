<div wire:key='post-{{ $post->id }}'>
  <div class="mt-6 p-6 bg-white rounded-2xl shadow-md border border-gray-400 m-auto">
    <div class="flex justify-between">
      <p class="p-4 text-lg font-semibold">
        <a href="{{ route("post.show", $post) }}" wire:navigate
          class="cursor-pointer hover:text-blue-500">{{ Str::limit($post->title, 80) }}</a>
      </p>
      <x-post-dropdown :$post />
    </div>
    {{-- <flux:button variant="danger" id="delete" x-on:click="$dispatch('delete-post', {id:'{{$post->id}}'})" >JS削除</flux:button> --}}
    {{-- <flux:button variant="danger" wire:click="delete({{ $post->id }})">LW削除</flux:button> --}}
    <hr class="w-full">
    <div class="mt-4 p-4">{!! Str::limit(Str::markdown($post->body), 60) !!}</div>

    @if (($post->photo_path))
      <div class="flex flex-wrap">
        @foreach ($post->photo_path as $path)
          <img class="h-20 w-auto" src="{{ Storage::disk('s3')->url($path) }}" alt="">
        @endforeach
      </div>
    @endif

    <div class="flex justify-between items-center p-4 text-sm">
      <div class="flex gap-2 items-center">
        <div>
            @auth
              @if (!auth()->user()->bookmarks->contains($post->id))
                <button title="記事をブックマーク" class="cursor-pointer"
                wire:click="bookmark({{ $post->id }})">
                  <flux:icon.bookmark variant="outline" size="12"></flux:icon.bookmark>
                </button>

                @else
                <button title="ブックマークを解除" class="cursor-pointer"
                wire:click="unbookmark({{ $post->id }})">
                  <flux:icon.bookmark variant="solid" size="12" class="text-yellow-400"></flux:icon.bookmark>
                </button>
              @endif
            @endauth
        </div>
        <div>{{ count($post->bookmarked) }}</div>
      </div>

      <div class="flex items-center">
        @if (($post->user->logo))
            <img src="{{ Storage::disk('s3')->url($post->user->logo) }}" alt="" class="h-8 w-auto rounded-full mr-4">
        @endif
        <p>
          <a href="{{ route("user.show", $post->user) }}" wire:navigate
            class="hover:text-blue-500 font-semibold">{{ $post->user->name }} </a>/
          {{ $post->created_at }}
        </p>
    </div>
  </div>
  {{-- <x-like-button :$post /> --}}
</div>
</div>
