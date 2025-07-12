<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Public: Applicant can browse all open jobs
Route::get('/jobs', [JobController::class, 'index']);

// Applicant applies to job
Route::middleware(['auth:sanctum', 'role:applicant'])->group(function () {
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'apply']);
});

// Employer views applicants for their job
Route::middleware(['auth:sanctum', 'role:employer'])->group(function () {
    Route::get('/employer/jobs/{jobId}/applicants', [ApplicationController::class, 'applicants']);
});

// Protected: Employer Job CRUD
Route::middleware(['auth:sanctum', 'role:employer'])->prefix('employer')->group(function () {
    Route::get('/jobs', [JobController::class, 'myJobs']);
    Route::post('/jobs', [JobController::class, 'store']);
    Route::put('/jobs/{id}', [JobController::class, 'update']);
    Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
