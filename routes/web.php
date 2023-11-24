<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
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
    return redirect()->route("login");
   /* return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);*/
});
Route::get("/swagger")
    ->name("swagger");

Route::get('/dashboard', function () {
    $applications = \App\Models\Application::query()
        ->where("user_id","=",\Illuminate\Support\Facades\Auth::user()->id)
        ->get();

    return Inertia::render('Dashboard',compact("applications"));
})->middleware(['auth'])->name('dashboard');
Route::get('/log-applications', function () {
    if(!Auth::user()->hasRole("admin")){
        return redirect()->back();
    }
    $applicationLogs= \App\Models\ApplicationsRequestLog::query()
        ->with("application.user")
       /* ->whereHas("application",function (\Illuminate\Contracts\Database\Query\Builder $builder){
            $builder
                ->where("user_id","=",\Illuminate\Support\Facades\Auth::user()->id);

        })*/
        ->get();

    return Inertia::render('LogApplication/IndexLogApplication',compact("applicationLogs"));
})->middleware(['auth'])->name('log-applications');


Route::middleware('auth')->group(function () {
    Route::resource("applications",\App\Http\Controllers\ApplicationController::class);
    Route::get("pre-registrations",[\App\Http\Controllers\PreRegistrationController::class,"index"])
        ->can("viewMy,App\Models\PreRegistration")
        ->name("pre-registrations.index");;
    Route::put("pre-registrations/{preRegistration}",[\App\Http\Controllers\PreRegistrationController::class,"update"])
        ->can("update,App\Models\PreRegistration,preRegistration")
        ->name("pre-registrations.update");;
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
