<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\PostForm;
use Flux\Flux;

new class extends Component {
    use WithFileUploads;

    public PostForm $form;

    public function save()
    {
        $this->form->store();
        Flux::toast("記事を投稿しました", variant: "success");
        return $this->redirect(route("post.index"), navigate: true);
    }

    public function deleteCurrentPhoto($i)
    {
        $this->form->deleteCurrentPhoto($i);
    }
};

?>

<div class="m-auto w-full mb-4">
  <x-page-header>新規投稿</x-page-header>
  <form wire:submit="save">

    <div class="mb-6">
      <flux:input wire:model="form.title" label="タイトル" placeholder="タイトル" />
    </div>

    <div class="mb-6">
      <flux:textarea wire:model.live="form.body" label="本文(マークダウン)" placeholder="ここに本文を入力" rows="10" resize="vertical">
      </flux:textarea>
    </div>

    <div class="mb-6">
        <p class="mb-2 text-sm font-medium">本文プレビュー</p>
        <div class="w-full border-1 rounded-lg p-2 shadow-sm">
            <div class="prose leading-6 mt-4 p-4">{!! Str::markdown($form->body) !!}</div>
        </div>
    </div>

    <div class="mb-6">
      <flux:input class="mb-6" wire:model="form.photos" type="file" label="写真を追加" multiple accept=".jpg, .jpeg, .png, .gif, .svg" />

      <div class="flex item-center">
        @if ($form->photos)
          @for ($i = 0; $i < count($form->photos); $i++)
            <div wire:key="{{ $i }}">
              <button class="cursor-pointer" type="button"
                wire:click="deleteCurrentPhoto({{ $i }})"><flux:icon.x-circle variant="solid"
                  class="absolute hover:opacity-60" />
                @if ($form->photos[$i]->isPreviewable())
                  <img class="h-20 w-auto" src="{{ $form->photos[$i]->temporaryUrl() }}" alt="">
                @else
                  {{-- 画像以外の場合の表示（アイコンやファイル名など） --}}
                  <div class="p-2 bg-gray-100">画像以外のファイルです</div>
                @endif
            </div>
          @endfor
        @endif
      </div>
    </div>


    <div class="mb-3">
      <flux:button type="submit" variant="primary" color="indigo">保存</flux:button>
    </div>
  </form>


</div>
