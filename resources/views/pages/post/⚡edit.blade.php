<?php

use App\Livewire\Forms\PostForm;
use Livewire\WithFileUploads;
use App\Models\Post;
use Livewire\Component;
use Flux\Flux;

new class extends Component {
    use WithFileUploads;
    public PostForm $form;

    public function mount(Post $post)
    {
        $this->form->setPost($post);
    }

    public function save()
    {
        $this->form->update();
        Flux::toast("記事を編集しました", variant: "success");
        return $this->redirect(route("post.index"), navigate: true);
    }

    public function deleteSavedPhoto($i)
    {
        $this->form->deleteSavedPhoto($i);
    }

    public function deleteCurrentPhoto($i)
    {
        $this->form->deleteCurrentPhoto($i);
    }
};
?>

<div class="m-auto w-full mb-4">
  <x-page-header>編集</x-page-header>
    <form wire:submit="save">

    <div class="mb-6">
      <flux:input wire:model="form.title" label="タイトル" placeholder="タイトル" />
    </div>

    <div class="mb-6">
      <flux:textarea wire:model="form.body" label="本文" placeholder="ここに本文を入力" rows="10" resize="vertical">
      </flux:textarea>
    </div>

    <div class="mb-6 ">
      <flux:input class="mb-6" wire:model="form.photos" type="file" label="写真を追加" accept=".jpg, .jpeg, .png, .gif, .svg" multiple />

      <div class="flex item-center">
        @if ($form->photo_path)

          @for ($i=0;$i<count($form->photo_path);$i++)
            <div wire:key="{{ $i }}">
              <button class="cursor-pointer" type="button" wire:click="deleteSavedPhoto({{ $i }})"><flux:icon.x-circle variant="solid" class="absolute hover:opacity-60"/>
              <img class="h-20 w-auto" src="{{ Storage::url($form->photo_path[$i]) }}" alt="">
              {{-- {{ var_dump($form->photo_path[$i]) }} --}}
            </div>
          @endfor

        @endif
        @if ($form->photos)
          @for ($i=0;$i<count($form->photos);$i++)
            <div wire:key="{{ $i }}">
              <button class="cursor-pointer" type="button"
              wire:click="deleteCurrentPhoto({{ $i }})"><flux:icon.x-circle variant="solid"
               class="absolute hover:opacity-60"/>
               @if ($form->photos[$i]->isPreviewable())
                  <img class="h-20 w-auto" src="{{ $form->photos[$i]->temporaryUrl() }}" alt="">
                @else
                  {{-- 画像以外の場合の表示（アイコンやファイル名など） --}}
                  <div class="p-2 bg-gray-100">画像以外のファイルです</div>
                @endif
            {{-- {{ var_dump($form->photos[$i]) }} --}}
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
