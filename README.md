# kimmirai_SPAブログ
![Static Badge](https://img.shields.io/badge/php-blue?style=for-the-badge&logo=php)
![Static Badge](https://img.shields.io/badge/Laravel-blue?style=for-the-badge&logo=laravel)
![Static Badge](https://img.shields.io/badge/Livewire-blue?style=for-the-badge&logo=livewire&logoColor=pink)
![Static Badge](https://img.shields.io/badge/TailwindCSS-gray?style=for-the-badge&logo=tailwindcss)
![Static Badge](https://img.shields.io/badge/Alpine.js-gray?style=for-the-badge&logo=alpinedotjs)
![Static Badge](https://img.shields.io/badge/PostgreSQL-green?style=for-the-badge&logo=postgresql)
![Static Badge](https://img.shields.io/badge/render-green?style=for-the-badge&logo=render)
![Static Badge](https://img.shields.io/badge/Supabase-green?style=for-the-badge&logo=Supabase)


## 概要
Laravelで個人製作したポートフォリオです。ブログ記事を投稿するSNSサービスで、SPAを採用しています。

<img width="1321" height="868" alt="Image" src="https://github.com/user-attachments/assets/196951a1-5257-4526-aaa1-4df619e0d2ee" />

## URL
<a href="https://kimmirai-2026blog-tokyo.onrender.com" target="_blank">https://kimmirai-2026blog-tokyo.onrender.com</a>

## 使用技術
- PHP 8.4.16
- Laravel 13.9.0
- livewire 4.3.1
- Tailwind CSS
- Alpine.js
- PostgreSQL
- Render
- Supabase

## アーキテクチャ図
<img width="827" height="657" alt="Image" src="https://github.com/user-attachments/assets/1e7a0c88-1f96-4e3a-b865-30580f7509e3" />

## 機能一覧
- ユーザーログイン、登録、削除
- ユーザー情報変更(名前、メールアドレス、パスワード、アイコン)
- 記事投稿関連
  - CRUD
  - 画像投稿
  - 記事のお気に入り登録・解除(非同期通信)
- ユーザー関連
  - フォロー登録・解除(非同期通信)

## 制作の背景
これまでに習得した言語やスキルを、ポートフォリオとして記録したいと思い作成しました。  
制作中に一番意識した点は、ユーザーの操作ストレスを可能な限り減らすことです。  
結果として、スクロール・スワイプのみでタイムラインを追う無限スクロールや  
インデックスページから画面遷移することなく記事のお気に入り登録・ユーザーのフォローを行う非同期通信の採用に至りました。
