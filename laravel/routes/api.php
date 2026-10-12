<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TrainingProgramController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\PaymentController;

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'getProductItem']);
Route::post('/products', [ProductController::class, 'createProduct']);
Route::put('/products/{id}', [ProductController::class, 'updateProduct']);
Route::delete('/products/{id}', [ProductController::class, 'deleteProduct']);

Route::get('/trainers', [TrainerController::class, 'getTrainers']);
Route::get('/trainers/{id}', [TrainerController::class, 'getTrainerItem']);
Route::post('/trainers', [TrainerController::class, 'createTrainer']);
Route::put('/trainers/{id}', [TrainerController::class, 'updateTrainer']);
Route::delete('/trainers/{id}', [TrainerController::class, 'deleteTrainer']);

Route::get('/clients', [ClientController::class, 'getClients']);
Route::get('/clients/{id}', [ClientController::class, 'getClientItem']);
Route::post('/clients', [ClientController::class, 'createClient']);
Route::put('/clients/{id}', [ClientController::class, 'updateClient']);
Route::delete('/clients/{id}', [ClientController::class, 'deleteClient']);

Route::get('/training-programs', [TrainingProgramController::class, 'getTrainingPrograms']);
Route::get('/training-programs/{id}', [TrainingProgramController::class, 'getTrainingProgramItem']);
Route::post('/training-programs', [TrainingProgramController::class, 'createTrainingProgram']);
Route::put('/training-programs/{id}', [TrainingProgramController::class, 'updateTrainingProgram']);
Route::delete('/training-programs/{id}', [TrainingProgramController::class, 'deleteTrainingProgram']);

Route::get('/memberships', [MembershipController::class, 'getMemberships']);
Route::get('/memberships/{id}', [MembershipController::class, 'getMembershipItem']);
Route::post('/memberships', [MembershipController::class, 'createMembership']);
Route::put('/memberships/{id}', [MembershipController::class, 'updateMembership']);
Route::delete('/memberships/{id}', [MembershipController::class, 'deleteMembership']);

Route::get('/payments', [PaymentController::class, 'getPayments']);
Route::get('/payments/{id}', [PaymentController::class, 'getPaymentItem']);
Route::post('/payments', [PaymentController::class, 'createPayment']);
Route::put('/payments/{id}', [PaymentController::class, 'updatePayment']);
Route::delete('/payments/{id}', [PaymentController::class, 'deletePayment']);
