<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id()->index();
            $table->string('title');
            $table->text('excerpt');
            $table->longText('markdown');
            $table->string('slug')->index()->unique();
            $table->timestampTz('published_at')->nullable();
            $table->foreignId('user_id');
            $table->foreignId('article_type_id');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
