<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Public pages
Route::get('/', [MarketingController::class, 'index'])->name('home');
Route::get('/docs', [DocsController::class, 'index'])->name('docs');
Route::get('/docs/open-source/getting-started', [DocsController::class, 'openSourceGettingStarted'])->name('docs.os.getting-started');
Route::get('/docs/open-source/configuration', [DocsController::class, 'openSourceConfiguration'])->name('docs.os.configuration');
Route::get('/docs/open-source/api-reference', [DocsController::class, 'openSourceApiReference'])->name('docs.os.api-reference');
Route::get('/docs/open-source/sdks', [DocsController::class, 'openSourceSdks'])->name('docs.os.sdks');
Route::get('/docs/cloud/getting-started', [DocsController::class, 'cloudGettingStarted'])->name('docs.cloud.getting-started');
Route::get('/docs/cloud/projects', [DocsController::class, 'cloudProjects'])->name('docs.cloud.projects');
Route::get('/docs/cloud/billing', [DocsController::class, 'cloudBilling'])->name('docs.cloud.billing');

// Authenticated dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/{project}/pause', [ProjectController::class, 'pause'])->name('projects.pause');
    Route::post('/projects/{project}/resume', [ProjectController::class, 'resume'])->name('projects.resume');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/checkout/{plan}', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/stripe/webhook', [WebhookController::class, 'handle'])->name('stripe.webhook');

require __DIR__.'/auth.php';
