<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LandingController;

Route::redirect('/', '/login');

// Authentication
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('live-search');

// Landing page route
Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['auth'])->group(function () {
    // Profile for all users
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // Applicant routes
    Route::middleware(['is_applicant'])->group(function () {
        Route::get('/dashboard/applicant', [ApplicantController::class, 'index'])->name('applicant.dashboard');
        Route::get('applicant/events', [EventController::class, 'applicantIndex'])->name('applicant.events.index');
        Route::post('applicant/events/{event}/join', [EventController::class, 'join'])->name('applicant.events.join');
        Route::get('applicant/events/{event}/certificate', [EventController::class, 'downloadCertificate'])->name('applicant.events.certificate');
        Route::get('applicant/events/{event}/registration-proof', [EventController::class, 'downloadRegistrationProof'])->name('applicant.events.registration-proof');
        
        // Applicant profile
        Route::get('/applicant/profile', [ProfileController::class, 'applicantProfile'])->name('applicant.profile');
        Route::get('/applicant/detailProfile', [ProfileController::class, 'applicantDetailProfile'])->name('applicant.detailProfile');
        Route::put('/applicant/updateProfile', [ProfileController::class, 'updateApplicantProfile'])->name('applicant.updateProfile');
        Route::put('/applicant/updatePassword', [ProfileController::class, 'updateApplicantPassword'])->name('applicant.updatePassword');
        Route::get('/applicant/educationProfile', [ProfileController::class, 'educationProfile'])->name('applicant.educationProfile');
        Route::post('/applicant/storeEducation', [ProfileController::class, 'storeEducation'])->name('applicant.storeEducation');
        Route::get('/applicant/editEducation/{id}', [ProfileController::class, 'editEducation'])->name('applicant.editEducation');
        Route::put('/applicant/updateEducation/{id}', [ProfileController::class, 'updateEducation'])->name('applicant.updateEducation');
        Route::delete('/applicant/deleteEducation/{id}', [ProfileController::class, 'deleteEducation'])->name('applicant.deleteEducation');
        Route::get('/applicant/experience', [ProfileController::class, 'experienceProfile'])->name('applicant.experienceProfile');
        Route::post('/applicant/experience', [ProfileController::class, 'storeExperience'])->name('applicant.storeExperience');
        Route::get('/applicant/experience/{id}/edit', [ProfileController::class, 'editExperience'])->name('applicant.editExperience');
        Route::put('/applicant/experience/{id}', [ProfileController::class, 'updateExperience'])->name('applicant.updateExperience');
        Route::delete('/applicant/experience/{id}', [ProfileController::class, 'deleteExperience'])->name('applicant.deleteExperience');
        Route::get('/events/history', [EventController::class, 'history'])->name('events.history');
    });

    // Recruiter routes
    Route::middleware(['is_recruiter'])->group(function () {
        Route::get('/dashboard/recruiter', [RecruiterController::class, 'index'])->name('recruiter.dashboard');
        Route::get('recruiter/events/create', [EventController::class, 'create'])->name('recruiter.events.create');
        Route::post('recruiter/events', [EventController::class, 'store'])->name('recruiter.events.store');
        Route::get('recruiter/events', [EventController::class, 'recruiterIndex'])->name('recruiter.events.index');
        Route::get('recruiter/events/{event}/edit', [EventController::class, 'edit'])->name('recruiter.events.edit');
        Route::put('recruiter/events/{event}', [EventController::class, 'update'])->name('recruiter.events.update');
        Route::delete('recruiter/events/{event}', [EventController::class, 'destroy'])->name('recruiter.events.destroy');
        Route::put('recruiter/events/{event}/close', [EventController::class, 'close'])->name('recruiter.events.close');
        Route::get('recruiter/events/{event}/report', [RecruiterController::class, 'downloadReport'])->name('recruiter.events.report');

        Route::post('events/update/applicant', [EventController::class, 'applicantUpdateStatus'])->name('recruiter.events.applicant.update');
        Route::post('events/delete/applicant', [EventController::class, 'applicantDelete'])->name('recruiter.events.applicant.delete');
        
        // Recruiter profile
        Route::get('/profile', [ProfileController::class, 'recruiterProfile'])->name('recruiter.profile');
        Route::get('/detailProfile', [ProfileController::class, 'recruiterDetailProfile'])->name('recruiter.detailProfile');
        Route::put('/updateProfile', [ProfileController::class, 'updateRecruiterProfile'])->name('recruiter.updateProfile');
        Route::put('/updatePassword', [ProfileController::class, 'updateRecruiterPassword'])->name('recruiter.updatePassword');
    });

    // Admin routes
    Route::middleware(['is_admin'])->group(function () {
        Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/accounts', [AdminController::class, 'accounts'])->name('admin.accounts');
        Route::get('/admin/events', [AdminController::class, 'events'])->name('admin.events');

        Route::get('/admin/account/edit/{id}', [AdminController::class, 'editAccount'])->name('admin.account.edit');
        Route::put('/admin/account/update/{id}', [AdminController::class, 'updateAccount'])->name('admin.account.update');
        Route::delete('/admin/account/delete/{id}', [AdminController::class, 'deleteAccount'])->name('admin.account.delete');
        Route::post('/admin/account/store', [AdminController::class, 'storeAccount'])->name('admin.account.store');

        Route::get('/admin/event/edit/{id}', [AdminController::class, 'editEvent'])->name('admin.event.edit');
        Route::put('/admin/event/update/{id}', [AdminController::class, 'updateEvent'])->name('admin.event.update');
        Route::delete('/admin/event/delete/{id}', [AdminController::class, 'deleteEvent'])->name('admin.event.delete');
        
        // New routes for admin create event
        Route::get('/admin/event/create', [AdminController::class, 'createEvent'])->name('admin.event.create');
        Route::post('/admin/event/store', [AdminController::class, 'storeEvent'])->name('admin.event.store');
        Route::get('/admin/event/{id}/report', [AdminController::class, 'downloadReport'])->name('admin.event.report');

        Route::put('/event/{id}/close', [AdminController::class, 'closeEvent'])->name('admin.event.close');
    });

    // Recruiter registration routes
    Route::middleware(['auth', 'is_applicant'])->group(function () {
        Route::get('/recruiter/register', [RecruiterController::class, 'showRegistrationForm'])->name('recruiter.register');
        Route::post('/recruiter/register', [RecruiterController::class, 'register'])->name('recruiter.register.submit');
    });
});