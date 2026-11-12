<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PaymentController;

// Публічні - реєстрація та логін
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Далі тільки з JWT токеном
Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);

    // Client (і вище) - читання
    Route::middleware('role:client')->group(function () {
        Route::get('/products', [ProductController::class, 'getProducts']);
        Route::get('/products/{id}', [ProductController::class, 'getProductItem']);

        Route::get('/trainers', [TrainerController::class, 'getTrainers']);
        Route::get('/trainers/{id}', [TrainerController::class, 'getTrainerItem']);

        Route::get('/clients', [ClientController::class, 'getClients']);
        Route::get('/clients/{id}', [ClientController::class, 'getClientItem']);

        Route::get('/training-programs', [TrainingProgramController::class, 'getTrainingPrograms']);
        Route::get('/training-programs/{id}', [TrainingProgramController::class, 'getTrainingProgramItem']);

        Route::get('/memberships', [MembershipController::class, 'getMemberships']);
        Route::get('/memberships/{id}', [MembershipController::class, 'getMembershipItem']);

        Route::get('/payments', [PaymentController::class, 'getPayments']);
        Route::get('/payments/{id}', [PaymentController::class, 'getPaymentItem']);
    });

    // Manager (і вище) - створення та оновлення
    Route::middleware('role:manager')->group(function () {
        Route::post('/products', [ProductController::class, 'createProduct']);
        Route::put('/products/{id}', [ProductController::class, 'updateProduct']);

        Route::post('/trainers', [TrainerController::class, 'createTrainer']);
        Route::put('/trainers/{id}', [TrainerController::class, 'updateTrainer']);

        Route::post('/clients', [ClientController::class, 'createClient']);
        Route::put('/clients/{id}', [ClientController::class, 'updateClient']);

        Route::post('/training-programs', [TrainingProgramController::class, 'createTrainingProgram']);
        Route::put('/training-programs/{id}', [TrainingProgramController::class, 'updateTrainingProgram']);

        Route::post('/memberships', [MembershipController::class, 'createMembership']);
        Route::put('/memberships/{id}', [MembershipController::class, 'updateMembership']);

        Route::post('/payments', [PaymentController::class, 'createPayment']);
        Route::put('/payments/{id}', [PaymentController::class, 'updatePayment']);
    });

    // Admin - видалення
    Route::middleware('role:admin')->group(function () {
        Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);
        Route::delete('/trainers/{id}', [TrainerController::class, 'deleteTrainer']);
        Route::delete('/clients/{id}', [ClientController::class, 'deleteClient']);
        Route::delete('/training-programs/{id}', [TrainingProgramController::class, 'deleteTrainingProgram']);
        Route::delete('/memberships/{id}', [MembershipController::class, 'deleteMembership']);
        Route::delete('/payments/{id}', [PaymentController::class, 'deletePayment']);
    });
});
