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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->constrained()->onUpdate('cascade');
            $table->string('name')->comment("コース名");
            $table->text('description')->nullable()->comment("説明");
            $table->integer('runtime')->comment("実行時間");
            $table->timestamp('deleted_at')->nullable()->comment("削除日");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
