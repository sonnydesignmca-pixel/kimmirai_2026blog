<flux:dropdown position="bottom" align="end" offset="-15" gap="2">
  <flux:button icon="ellipsis-vertical"></flux:button>
  <flux:menu>
    <flux:menu.item href="{{ route('user.show', $post->user) }}">ユーザーのプロフィール</flux:menu.item>

   @auth
     @can("notMyPost", $post)
       @if (!auth()->user()->followings->contains($post->user->id))
         <flux:menu.item wire:click="followFromPost({{ $post->id }})">ユーザーをフォロー</flux:menu.item>
       @else
         <flux:menu.item wire:click="unfollowFromPost({{ $post->id }})">フォロー解除</flux:menu.item>
       @endif
     @endcan

     @if (!auth()->user()->bookmarks->contains($post->id))
       <flux:menu.item wire:click="bookmark({{ $post->id }})">記事をブックマーク</flux:menu.item>
     @else
       <flux:menu.item wire:click="unbookmark({{ $post->id }})">ブックマークを解除</flux:menu.item>
     @endif
     @can("update", $post)
       <hr class="w-full">
       <flux:menu.item href="{{ route('post.edit', $post) }}">編集</flux:menu.item>
       <flux:menu.item wire:click="deletePost({{ $post->id }})">削除</flux:menu.item>
     @endcan
   @endauth
  </flux:menu>
</flux:dropdown>
