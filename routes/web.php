<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TheDashboardController;
use App\Http\Controllers\InputFrontController;
use App\Http\Controllers\TheDivisionController;


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route("dashboard");
});

Route::get('/dashboard', [TheDashboardController::class,"index"])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
	
	Route::get("/add", [InputFrontController::class,"index"])->name("add");
	
	Route::get("/hello", function() {
		return response()->json("hello world");
	})->name("hello");

	Route::post("/saveinput", [InputFrontController::class,"save"])->name("save");

	Route::get("/getactrecs/{div?}",[InputFrontController::class,"getActivityRecords"])->name("getActivityRecords");

	Route::get("/removeitem/{itemid?}",[InputFrontController::class,"removeitem"])->name("removeitem");

	Route::get("/getdetails/{theidid?}",[InputFrontController::class,"getdetails"])->name("getdetails");

	Route::get("/employee",[TheDashboardController::class,"employeemgt"])->name("employeemgt");
	Route::post("/saveemp",[TheDivisionController::class,"saveemp"])->name("saveemp");

	Route::get("/fundsource",[InputFrontController::class,"fundsource"])->name("fundsource");
	Route::post("/savefund",[InputFrontController::class,"savefund"])->name('savefund');
});

Route::get("/getfunds", [TheDashboardController::class,"getfunds"])->name("getfunds");

Route::get('/view/{div?}', [InputFrontController::class,"view"])->name("view");

require __DIR__.'/auth.php';
