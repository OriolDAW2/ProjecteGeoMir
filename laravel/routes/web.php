<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostsController;

use App\Http\Controllers\PlacesController;


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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function (Request $request) {
   $message = 'Loading welcome page';
   Log::info($message);
   $request->session()->flash('info', $message);
   return view('welcome');
});

Route::resource('files', FileController::class)
->middleware(['auth', 'permission:files']);

Route::resource('posts', PostsController::class)
->middleware(['auth', 'permission:posts']);

Route::resource('places', PlacesController::class)
->middleware(['auth', 'permission:places']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/language/{locale}', [App\Http\Controllers\LanguageController::class, 'language']);

Route::post('/posts/{post}/like', [App\Http\Controllers\PostsController::class, 'like'])->name('posts.like');

Route::post('/posts/{post}/unlike', [App\Http\Controllers\PostsController::class, 'unlike'])->name('posts.unlike');

require __DIR__.'/auth.php';