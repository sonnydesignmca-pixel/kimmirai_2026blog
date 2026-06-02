<?php

use App\Models\Post;
use App\Livewire\Traits\PostTrait;
use App\Livewire\Traits\UserTrait;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
// use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Url;

new #[Title('記事一覧')] class extends Component {

    use PostTrait;
    use UserTrait;

    #[Url(as:'q')]
    public $search = "";
    public $amount = 5;

    public $showOnlyFollowing = false;

    public function updatedSearch()
    {
        $this->dispatch("scrollTop");
    }

    public function loadMore()
    {
        $this->amount += 5;
    }

    public function toggleFollowing($toggle)
    {
        // $this->reset('search');
        $this->showOnlyFollowing = $toggle;
    }


    #[Computed]
    public function posts()
    {
        $query = Post::query();

        // 1. フォローフィルターの適用
        if ($this->showOnlyFollowing) {
            $query->whereIn('user_id', function ($subQuery) {
                $subQuery->select('followed_id')
                    ->from('follows')
                    ->where('follower_id', auth()->id());
            });
        }

        // 2. 検索ワードの適用（whereの重複を削除し、グループ化して安全に）
        if ($this->search) {
            $query->where(function ($q) {
                $q->where("title", "like", "%{$this->search}%")
                    ->orWhere("body", "like", "%{$this->search}%")
                    ->orWhereHas('user', function ($subQuery) {
                        $subQuery->where('name', 'like', "%{$this->search}%");
                    });
            });
        }

        // 3. 指定した件数分だけイーガーロードして取得
        return $query->with("user")->latest()->take($this->amount)->get();
    }
};
?>

<div>

    <x-page-header>記事一覧</x-page-header>
    <div class="w-full sticky top-1 max-md:top-15 z-1">
        <flux:input wire:model.live.debounce.500="search" icon="magnifying-glass"
            placeholder="記事を検索 (タイトルor本文or投稿ユーザー)" />
    </div>

    <div class="mt-6">
        <button @class([
            "text-gray-200 p-2 rounded-md",
            "bg-blue-700 " => !$showOnlyFollowing,
            "bg-gray-500 hover:bg-blue-700" => $showOnlyFollowing,
        ]) wire:click='toggleFollowing(false)' {{ !$showOnlyFollowing ? "disabled" : "" }}>
            全ての投稿
        </button>
        @auth
            <button @class([
                "text-gray-200 p-2 rounded-md",
                "bg-blue-700" => $showOnlyFollowing,
                "bg-gray-500 hover:bg-blue-700" => !$showOnlyFollowing,
            ]) wire:click='toggleFollowing(true)' {{ $showOnlyFollowing ? "disabled" : "" }}>
                フォローしている投稿
            </button>
        @endauth
    </div>

    @if (count($this->posts) == 0)
        <div class="pt-2">検索結果がありません</div>
    @endif

    <div class="mb-4">
        @foreach ($this->posts as $post)
            <x-post-preview :$post></x-post-preview>
        @endforeach
    </div>

    <div>
        <button class="fixed bottom-6 right-6 cursor-pointer" wire:click="$dispatch('scrollTop')" title="ページトップへ">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                class="size-14 text-blue-500 opacity-70 hover:opacity-100">
                <path fill-rule="evenodd"
                    d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.53 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v5.69a.75.75 0 0 0 1.5 0v-5.69l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z"
                    clip-rule="evenodd" />
            </svg>

        </button>
    </div>

    {{-- 読み込み中のインジケーター --}}
    <div wire:loading wire:target="loadMore" class="w-full text-center py-4">
        <flux:icon.loading class="m-auto"></flux:icon.loading>
    </div>

    {{-- 📌 修正ポイント：ローディング中でない、かつデータがまだ存在する可能性がある時だけ監視する --}}
    <div wire:loading.remove wire:target="loadMore">
        @if (count($this->posts) >= $amount)
            <div wire:intersect="loadMore" class="h-4 w-full bg-transparent"></div>
        @endif
    </div>

    {{-- {{ $this->posts->links() }} --}}

</div>

{{-- 検索ワード更新時にページトップへ戻る --}}
<script>
    document.addEventListener('scrollTop', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
