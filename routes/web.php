<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [FormController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Form Builder Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}/edit', [FormController::class, 'edit'])->name('forms.edit');
    Route::put('/forms/{form}', [FormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{form}', [FormController::class, 'destroy'])->name('forms.destroy');
    
    // Question management
    Route::post('/forms/{form}/questions', [FormController::class, 'addQuestion'])->name('forms.questions.add');
    Route::put('/forms/{form}/questions/{question}', [FormController::class, 'updateQuestion'])->name('forms.questions.update');
    Route::delete('/forms/{form}/questions/{question}', [FormController::class, 'deleteQuestion'])->name('forms.questions.delete');
    Route::post('/forms/{form}/reorder', [FormController::class, 'reorderQuestions'])->name('forms.questions.reorder');
    Route::post('/forms/{form}/accepting', [FormController::class, 'setAccepting'])->name('forms.setAccepting');
    
    // Analytics
    Route::get('/forms/{form}/results', [AnalyticsController::class, 'showResults'])->name('forms.results');
    Route::get('/forms/{form}/export', [AnalyticsController::class, 'exportCsv'])->name('forms.export');
});


// Public Form Routes (no authentication required)
Route::get('/f/{form:public_token}', [ResponseController::class, 'showPublicForm'])->name('forms.public');
Route::post('/f/{form:public_token}/submit', [ResponseController::class, 'submitForm'])->name('forms.submit');
Route::get('/f/{form:public_token}/success', [ResponseController::class, 'showSuccess'])->name('forms.success');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
