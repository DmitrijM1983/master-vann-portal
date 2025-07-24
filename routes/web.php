<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TypeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Стартовая страница
Route::get('/', [UserController::class, 'index'])->name('index')->middleware('check_auth');

Route::middleware('guest')->group(function () {
    //Регистрация и авторизация
    Route::get('/registration', [AuthController::class, 'registrationForm'])->name('register_form');
    Route::post('/registration', [AuthController::class, 'registration'])->name('register');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login_form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    //Сброс пароля
    Route::get('/forgot-password', [AuthController::class, 'passwordRequest'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->name('password.email')
        ->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'passwordUpdate'])->name('password.update');
});

//Виды реставрации(Подробнее...)
Route::get('/enamel', [TypeController::class, 'enamelInfo'])->name('enamel');
Route::get('/acrylic', [TypeController::class, 'acrylicInfo'])->name('acrylic');
Route::get('/liner', [TypeController::class, 'linerInfo'])->name('liner');

//Поиск мастера
Route::get('/search', [MasterController::class, 'searchForm'])->name('search_form');
Route::post('/search', [MasterController::class, 'search'])->name('search');
Route::get('/master_card/{user}', [MasterController::class, 'masterCard'])->name('master_card');

//Подтверждение почты
Route::middleware('auth')->group(function () {
    Route::get('/verify-notification', function () {
        return view('auth.notification');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('profile', ['id' => $request->route('id')]);
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:3,1')->name('verification.send');
});

Route::middleware(['auth', 'verified'])->group(function () {
    //Личный кабинет мастера
    Route::get('/profile/{id}', [MasterController::class, 'masterAccount'])->name('profile');
    //Редактировать личные данные
    Route::post('/profile/{id}/update', [UserController::class, 'updateProfile'])->name('profile_update');
    //Редактировать данные мастера
    Route::post('/profile/{id}/master_update', [MasterController::class, 'updateMasterInfo'])->name('master_info_update');
    //Установить город
    Route::post('/profile/{id}/set_city', [MasterController::class, 'setCity'])->name('set_city');
    //Удалить город
    Route::delete('/profile/{id}/destroy_city', [MasterController::class, 'destroyCity'])->name('destroy_city');
    //Выбрать услуги
    Route::post('profile/{id}/services', [MasterController::class, 'setServices'])->name('services');
    //Фотографии работ
    Route::post('profile/{id}/job_images', [MasterController::class, 'setJobImages'])->name('job_images');
    Route::put('profile/{id}/edit_job_image', [MasterController::class, 'editJobImage'])->name('edit_job_image');
    Route::delete('profile/{id}/job_images/{image_id}', [MasterController::class, 'deleteJobImage'])->name('delete_job_image');
    //Выход
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    //Сообщения
    Route::post('/message', [ConnectionController::class, 'sendMessage'])->name('message');
    Route::post('/answer', [ConnectionController::class, 'sendAnswer'])->name('answer');
    Route::post('/mark_messages_as_read', [ConnectionController::class, 'markMessagesAsRead'])->name('mark-messages_as_read');
    Route::post('/mark_answer_as_read', [ConnectionController::class, 'markAnswerAsRead'])->name('mark_answer_as_read');
//Заявка
    Route::post('/order', [ConnectionController::class, 'makeOrder'])->name('order');
//Отзыв
    Route::post('/feedback', [ConnectionController::class, 'sendFeedback'])->name('feedback');
    Route::post('/answer_feedback', [ConnectionController::class, 'sendAnswerFeedback'])->name('answer_feedback');
    Route::post('/mark_feedbacks_as_read', [ConnectionController::class, 'markFeedbacksAsRead'])->name('mark_feedbacks_as_read');

//Отчет
    Route::post('report', [MasterController::class, 'saveReport'])->name('report');
});

Route::post('/support', [ConnectionController::class, 'contactSupport'])->name('support');



