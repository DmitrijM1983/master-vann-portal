<?php

use App\Models\Service;
use App\Models\User;
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
        Schema::dropIfExists('feedbacks');

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('master_id');
            $table->integer('user_id')->nullable();
            $table->integer('grade')->nullable()->comment('Оценка');
            $table->string('content')->nullable()->comment('Отзыв');
            $table->enum('service_provided', [0, 1])->comment('Услуга оказана');
            $table->integer('service_id')->nullable();
            $table->text('images')->nullable()->comment('Фотографии работы');
            $table->string('answer', 255)->nullable()->comment('Ответ мастера на отзыв');
            $table->timestamps();
        });
    }

    /**
     * Обратная миграция.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
