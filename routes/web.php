<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::post.index')->name('post.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    Route::livewire('/dashboard/post', 'pages::dashboard.post.list')->name('dashboard.post');
});


Route::livewire('/post/create', 'pages::post.create')->middleware('auth')->name('post.create');
Route::livewire('/post/{post}/edit', 'pages::post.edit')->middleware('auth')->name('post.edit');
Route::livewire('/post/{post}', 'pages::post.show')->name('post.show');


Route::livewire('/user/{user}', 'pages::user.show')->name('user.show');
Route::livewire('/user/{user}/followings', 'pages::user.followings')->name('user.followings');
Route::livewire('/user/{user}/followers', 'pages::user.followers')->name('user.followers');
Route::livewire('/user/{user}/posts', 'pages::user.posts')->name('user.posts');
Route::livewire('/user/{user}/bookmarks', 'pages::user.bookmarks')->name('user.bookmarks');

require __DIR__ . '/settings.php';
