<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Mengubah kolom published_at menjadi nullable dan type date
            $table->date('published_at')->nullable()->change();

            // Mengubah kolom status menjadi enum dengan nilai default 'draft'
            $table->enum('status', ['draft', 'publish'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Mengembalikan kolom published_at menjadi datetime
            $table->dateTime('published_at')->nullable(false)->change();

            // Mengembalikan kolom status menjadi smallInteger
            $table->smallInteger('status')->change();
        });
    }
};
