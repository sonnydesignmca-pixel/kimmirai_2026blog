<?php


use App\Livewire\Traits\PostTrait;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('投稿した記事')] class extends Component{
    use WithPagination;
    use PostTrait;

    public $search = "";

    #[Computed]
    public function posts()
    {
        $query = Post::query();

        if ($this->search) {
            $query->
                where(function ($query) {
                    $query->where("title", "like", "%{$this->search}%")->orWhere("body", "like", "%{$this->search}%");
                });
        }
        return $query->where('user_id', '=', Auth::user()->id)->with("user")->latest()->paginate(10, pageName: 'posts-page');
    }

    public function updatedSearch()
    {
        $this->resetPage('posts-page');
    }


    // public function togglePublished($toggle)
    // {
    //     $this->resetPage(pageName: 'posts-page');
    //     $this->showOnlyPublished = $toggle;
    // }
    // public function showPublished()
    // {
    //     $this->resetPage(pageName: 'posts-page');
    //     $this->showOnlyPublished = true;
    // }
    //ファイル命名でlivewireが自動でViewを検知するので
    //viewを返すだけのrenderなら不要
    // public function render()
    // {
    //     return view('livewire.post-list');
    // }
}

?>

<div>

    <div class="w-full sticky top-1 max-md:top-15 z-1">
        <flux:input wire:model.live.debounce.500="search" icon="magnifying-glass" placeholder="記事を検索" />
    </div>

    @if (count($this->posts) == 0)
        <div class="pt-2">検索結果がありません</div>
    @endif

    <div class="my-3">{{ $this->posts->links(data: ["scrollTo" => "false"]) }}</div>
    @foreach ($this->posts as $post)
        <x-post-preview :$post></x-post-preview>

    @endforeach
    </tbody>
    </table>
    <div class="my-3">{{ $this->posts->links(data: ["scrollTo" => "false"]) }}</div>



</div>
