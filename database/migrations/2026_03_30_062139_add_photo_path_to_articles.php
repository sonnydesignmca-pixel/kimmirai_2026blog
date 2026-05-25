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
    public function up(): void
    {
        DB::statement('ALTER TABLE posts ALTER COLUMN photo_path TYPE json USING photo_path::json');

        // 型以外の変更（nullableなど）を通常のスキーマで行う
        Schema::table('posts', function (Blueprint $table) {
            $table->json('photo_path')->nullable()->change();
        });

        //こっちはsqlite用
        // Schema::table('posts', function (Blueprint $table) {
        //     $table->string('photo_path')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
