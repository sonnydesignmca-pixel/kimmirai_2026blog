<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // PostgreSQL（Render環境）の場合のみ、生のSQLでUSINGを指定して型変更する
        if (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE posts ALTER COLUMN photo_path TYPE json USING photo_path::json');

            // 型以外の変更（nullableなど）を通常のスキーマで行う
            Schema::table('posts', function (Blueprint $table) {
                $table->json('photo_path')->nullable()->change();
            });
        } else {
            // ローカル（SQLiteなど）用の従来の記述
            Schema::table('posts', function (Blueprint $table) {
                $table->json('photo_path')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // ロールバック時はカラムごと削除する
            $table->dropColumn('photo_path');
        });
    }};
