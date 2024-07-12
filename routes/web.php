<?php

use App\Exports\UsersExport;
use App\Http\Controllers\ProfileController;
use App\Jobs\ExportUsersJob;
use App\Models\Download;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', ['users' => User::paginate()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/export', function (){
    ExportUsersJob::dispatch(request()->user())->onQueue('default');
    return response()->json(true);
})->middleware(['auth', 'verified'])->name('export');

Route::get('/files', function (){
    return Inertia::render('Files', ['files' => \App\Models\Download::all()->toArray()]);
})->name('files');

Route::get('/download/{download}', function (\Illuminate\Http\Request $request, Download $download){
    return \Illuminate\Support\Facades\Storage::download($download->file_name);
})->name('download');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
