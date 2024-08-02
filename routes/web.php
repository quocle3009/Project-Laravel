<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

// Task routes with 'auth' middleware
Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class);
    // Route::get('/tasks/search', [TaskController::class, 'search'])->name('tasks.search');

    // // Route::get('/tasks', [TaskController::class, 'index'])
    // //     ->name('tasks.index');
    // // Route::post('/tasks', [TaskController::class, 'store'])
    // //     ->name('tasks.store');
    // // Route::get('/tasks/create', [TaskController::class, 'create'])
    // //     ->name('tasks.create');
    // // Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
    // //     ->name('tasks.destroy');
    // // Route::get('/tasks/{tasks}/edit', [TaskController::class, 'edit'])
    // //     ->name('tasks.edit');
    // // Route::put('/tasks/{task}', [TaskController::class, 'update'])
    // //     ->name('tasks.update');
    // // search route
});

// Authentication routes
Auth::routes();

// Redirect after login
Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->name('home');

// Guest middleware group for login and register routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
        ->name('register');
});