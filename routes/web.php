<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::get('/projects/', [ProjectsController::class, 'index'])->name('index');
    Route::get('/projects/fetch', [ProjectsController::class, 'fetch']);
    Route::post('/projects/save', [ProjectsController::class, 'store']);
    Route::get('/projects/{project}/tasks', [ProjectsController::class, 'tasks']);

    Route::get('{project}/tasks/fetch', [TaskController::class, 'fetch']);
    Route::post('{project}/tasks/save', [TaskController::class, 'store']);
    Route::post('{task}/tasks/update', [TaskController::class, 'update']);
    Route::post('{project}/tasks/update-priority', [TaskController::class, 'updatePriority']);
    Route::delete('{task}/tasks/destroy', [TaskController::class, 'destroy']);
});

require __DIR__.'/auth.php';
