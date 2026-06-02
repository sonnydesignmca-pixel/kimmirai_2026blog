<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Livewire\WithFileUploads;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;
    use WithFileUploads;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     */

    public $logoPath = null;
    public function create(array $input): User
    {

        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'logo' => 'nullable|max:1024|mimes:jpeg,png,jpg,gif,svg'
        ])->validate();




        if (isset($input['logo'])) {
            $this->logoPath = $input['logo']->storePublicly('profile-logos', ['disk'=>'s3']);
            }


        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role'=>'',
            'logo' => $this->logoPath,
        ]);
    }
}
