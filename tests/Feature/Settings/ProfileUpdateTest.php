<?php

use App\Models\User;
use Livewire\Livewire;

// 👈 ここにbeforeEachを追加して、AWSのダミー環境変数をセットします
beforeEach(function () {
    config(['filesystems.disks.s3.key' => 'dummy-key']);
    config(['filesystems.disks.s3.secret' => 'dummy-secret']);
    config(['filesystems.disks.s3.region' => 'us-east-1']);
    config(['filesystems.disks.s3.bucket' => 'dummy-bucket']);

    // AWS SDKが直接参照する環境変数も念のためダミーで埋めます
    putenv('AWS_ACCESS_KEY_ID=dummy-key');
    putenv('AWS_SECRET_ACCESS_KEY=dummy-secret');
    putenv('AWS_DEFAULT_REGION=us-east-1');
    putenv('AWS_BUCKET=dummy-bucket');
});

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-modal')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(auth()->check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-modal')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
