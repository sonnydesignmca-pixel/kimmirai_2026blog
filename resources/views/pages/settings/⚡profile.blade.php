<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

new #[Title("プロフィール設定")] class extends Component {
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = "";
    public string $email = "";
    #[Validate('nullable|max:1024|mimes:jpeg,png,jpg,gif,svg')]
    public $logo = null;
    public $logo_path = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->logo_path = Auth::user()->logo;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate($this->profileRules($user->id));

        $user->fill($validated);

        if ($user->isDirty("email")) {
            $user->email_verified_at = null;
        }
        if ($this->logo) {
            if($this->logo_path){
            Storage::disk('s3')->delete($this->logo_path);
            }
            $user->logo = $this->logo->storePublicly('profile-logos', ['disk' => 's3']);
        }

        $user->save();

        Flux::toast(variant: "success", text: "プロフィールを更新しました");
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route("dashboard", absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __("A new verification link has been sent to your email address."));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && !Auth::user()->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        return !Auth::user() instanceof MustVerifyEmail || (Auth::user() instanceof MustVerifyEmail && Auth::user()->hasVerifiedEmail());
    }

    public function deleteSavedLogo()
    {
        $this->logo_path = null;
    }

    public function deleteCurrentlogo()
    {
        $this->logo = null;
    }
};
?>

<section class="w-full">
    @include("partials.settings-heading")

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" subheading="名前とメールアドレスを変更します">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6" enctype="multipart/form-data">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __("Your email address is unverified.") }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __("Click here to re-send the verification email.") }}
                            </flux:link>
                        </flux:text>

                    </div>
                @endif
            </div>

            <div class="flex items-center">
                <flux:input wire:model="logo" name="logo" label="ユーザーロゴを変更" type="file" accept=".jpg, .jpeg, .png, .gif, .svg" />
                @if ($this->logo)

                    @if ($this->logo->isPreviewable())
                        <img class="h-15 w-auto" src="{{ $this->logo->temporaryUrl() }}" alt="">
                    @endif

                @elseif (isset(Auth::user()->logo))
                    <img class="h-15 w-auto" src="{{ Storage::disk('s3')->url($this->logo_path) }}" alt="">
                @endif

            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit" data-test="update-profile-button">
                    {{ __("Save") }}
                </flux:button>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:pages::settings.delete-user-form />
        @endif
    </x-pages::settings.layout>
</section>
