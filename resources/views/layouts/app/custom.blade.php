<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
  {{-- PC版サイドバー --}}
  <flux:sidebar sticky collapsible breakpoint="768px"
    class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.header>
      <flux:sidebar.brand href="{{ route('post.index') }}" logo="https://fluxui.dev/img/demo/logo.png"
        logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png" name="SPAポートフォリオ" />
      <flux:sidebar.collapse />
    </flux:sidebar.header>

    <flux:sidebar.nav>
        <flux:sidebar.item icon="inbox" href="{{route('post.index')}}" wire:navigate>記事一覧</flux:sidebar.item>

      @auth
      <flux:sidebar.item icon="document-text" href="{{ route('post.create') }}"  wire:navigate>新規投稿</flux:sidebar.item>
      <flux:sidebar.group icon="user-circle" expandable :expanded="true" heading="ユーザー管理" class="grid"  wire:navigate>
          <flux:sidebar.item href="{{ route('user.followings',Auth::user()) }}"  wire:navigate>フォロー</flux:sidebar.item>
          <flux:sidebar.item href="{{ route('user.followers',Auth::user()) }}"  wire:navigate>フォロワー</flux:sidebar.item>
          <flux:sidebar.item href="{{ route('user.posts',Auth::user()) }}"  wire:navigate>投稿した記事</flux:sidebar.item>
          <flux:sidebar.item href="{{ route('user.bookmarks',Auth::user()) }}"  wire:navigate>保存した記事</flux:sidebar.item>
        </flux:sidebar.group>
        <flux:sidebar.item icon="user" href="{{ route('user.show',Auth::user()->id) }}" wire:navigate>マイプロフィール</flux:sidebar.item>
        @endauth

    </flux:sidebar.nav>
    <flux:sidebar.spacer />
    <flux:sidebar.nav>
    </flux:sidebar.nav>
    @auth
        <x-desktop-user-menu class="hidden md:block" :name="auth()->user()->name" />
    @endauth
    @guest
    <flux:sidebar.item icon="arrow-right-end-on-rectangle" href="{{ route('login') }}">ログイン</flux:sidebar.item>
    <flux:sidebar.item icon="user-plus" href="{{ route('register') }}">ユーザー登録</flux:sidebar.item>
    @endguest
  </flux:sidebar>

  {{-- モバイル版ヘッダー --}}
  <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 md:hidden top-0 sticky">
    <flux:brand href="#" logo="https://fluxui.dev/img/demo/logo.png" name="SPAポートフォリオ"
      class="max-md:hidden dark:hidden" />
    <flux:brand href="{{ route('dashboard') }}" logo="https://fluxui.dev/img/demo/dark-mode-logo.png" name="Acme Inc."
      class="max-md:hidden! hidden dark:flex" />
    <flux:sidebar.toggle class="md:hidden" icon="bars-3" inset="left" />
    <flux:navbar class="-mb-px md:hidden">
        <flux:navbar.item icon="inbox" href="{{route('post.index')}}" wire:navigate></flux:navbar.item>

        @auth
        <flux:navbar.item icon="document-text" href="{{ route('post.create') }}"  wire:navigate></flux:navbar.item>
        <flux:dropdown class="md:hidden">
            <flux:navbar.item icon="user-circle"></flux:navbar.item>
            <flux:navmenu>
                <flux:navmenu.item href="{{ route('user.followings',Auth::user()) }}">フォロー</flux:navmenu.item>
                <flux:navmenu.item href="{{ route('user.followers',Auth::user()) }}">フォロワー</flux:navmenu.item>
                <flux:navmenu.item href="{{ route('user.posts',Auth::user()) }}">投稿した記事</flux:navmenu.item>
                <flux:navmenu.item href="{{ route('user.bookmarks',Auth::user()) }}">保存した記事</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
        <flux:navbar.item icon="user" href="{{ route('user.show',Auth::user()->id) }}" wire:navigate></flux:navbar.item>
        @endauth

    </flux:navbar>
    <flux:spacer />
    @auth
        <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />

                    <flux:menu>

                            <flux:menu.radio.group>
                                <div class="p-0 text-sm font-normal">
                                    <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                        <flux:avatar
                                            :name="auth()->user()->name"
                                            :initials="auth()->user()->initials()"
                                        />

                                        <div class="grid flex-1 text-start text-sm leading-tight">
                                            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                        </div>
                                    </div>
                                </div>
                            </flux:menu.radio.group>


                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                設定
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer"
                                data-test="logout-button"
                            >
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
    @endauth

    @guest

    <flux:navbar.item icon:trailing="arrow-right-end-on-rectangle" href="{{ route('login') }}">ログイン</flux:navbar.item>
    <flux:navbar.item icon:trailing="user-plus" href="{{ route('register') }}">ユーザー登録</flux:navbar.item>
    @endguest
  </flux:header>

    {{ $slot }}

  @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts

    </body>

</html>
