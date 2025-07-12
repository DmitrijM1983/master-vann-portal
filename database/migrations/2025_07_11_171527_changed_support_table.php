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
        Schema::table('support', function (Blueprint $table) {
            $table->integer('user_id')->nullable()->after('id');
            $table->string('answer')->nullable()->after('photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('answer');
        });
    }
};
