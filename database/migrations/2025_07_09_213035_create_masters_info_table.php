<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('masters_info', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('master_photo', 255)->nullable()->comment('Фото мастера');
            $table->integer('experience')->nullable()->comment('Опыт(лет)');
            $table->integer('guarantee')->nullable()->comment('Гарантия(лет)');
            $table->float('rating')->nullable();
            $table->text('description')->nullable()->comment('О себе');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masters_info');
    }
};
