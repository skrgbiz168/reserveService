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
        Schema::table('reserves', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable()->comment("削除日(フラグを兼ねる)");
            $table->string('refund_id')->nullable()->comment("返金ID");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reserves', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('refund_id');
        });
    }
};
