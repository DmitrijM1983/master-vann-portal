<?php

use App\Models\Service;
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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->integer('master_id');
            $table->foreignIdFor(User::class)->nullable()->comment('Пользователь который создал отзыв');
            $table->foreignIdFor(Service::class)->nullable();
            $table->integer('grade')->nullable()->comment('Оценка');
            $table->string('content')->nullable()->comment('Отзыв');
            $table->enum('service_provided', [0, 1])->comment('Услуга оказана');
            $table->text('images')->nullable()->comment('Фотографии работы');
            $table->string('answer', 255)->nullable()->comment('Ответ мастера на отзыв');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
