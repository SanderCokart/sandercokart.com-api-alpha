<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('email_resets', function (Blueprint $table) {
            $table->integer('user_id')->index();
            $table->string('token');
            $table->timestampTz('created_at');
            $table->timestampTz('expires_at')->default(now()->addMinutes(60)->toDateTimeLocalString());
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('email_resets');
    }
};
