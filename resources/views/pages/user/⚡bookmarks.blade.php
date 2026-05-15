<?php


use App\Livewire\Traits\UserTrait;
use App\Livewire\Traits\PostTrait;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Url;

new #[Title('ブックマークした記事')] class extends Component{
    use WithPagination;
    use UserTrait;
    use PostTrait;

    public User $user;

    #[Url(as: 'q')]
    public $search = "";


    #[Computed]
    public function posts()
    {
    //    return $this->user->bookmarks()->latest()->paginate(5, pageName: 'bookmarks-page');
    $query = Post::query();

        $query->whereIn('id',function($subQuery){
            $subQuery->select('post_id')
            ->from('bookmarked_posts')
            ->where('user_id',$this->user->id);
        });

        if ($this->search) {
            $query->where(function ($q) {
                $q->where("title", "like", "%{$this->search}%")
                    ->orWhere("body", "like", "%{$this->search}%")
                    ->orWhereHas('user', function ($subQuery) {
                        $subQuery->where('name', 'like', "%{$this->search}%");
                    });
                });
        }
        return $query->with("user")->latest()->paginate(10, pageName: 'posts-page');

    }
    public function updatedSearch()
    {
        $this->resetPage('posts-page');
    }


}

?>

<div>
    <x-page-header>保存した記事</x-page-header>

    <div class="w-full sticky top-1 max-md:top-15 z-1">
        <flux:input wire:model.live.debounce.500="search" icon="magnifying-glass" placeholder="記事を検索 (タイトルor本文or投稿ユーザー)" />
    </div>

    @if (count($this->posts) == 0)
        <div class="pt-2">検索結果がありません</div>
    @endif

<div class="my-3">{{ $this->posts->links(data: ["scrollTo" => "false"]) }}</div>
    @foreach ($this->posts as $post)
        <x-post-preview :$post></x-post-preview>

    @endforeach
<div class="my-3">{{ $this->posts->links(data: ["scrollTo" => "false"]) }}</div>

</div>
