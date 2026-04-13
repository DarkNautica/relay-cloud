<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TwoFactorChallengeController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\WebhookConfigController;
use App\Http\Controllers\InspectorController;
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
Route::get('/docs/guides/nextjs', [DocsController::class, 'nextjs'])->name('docs.guides.nextjs');
Route::get('/docs/guides/rails', [DocsController::class, 'rails'])->name('docs.guides.rails');
Route::get('/docs/guides/django', [DocsController::class, 'django'])->name('docs.guides.django');
Route::get('/docs/guides/node', [DocsController::class, 'node'])->name('docs.guides.node');
Route::get('/docs/guides/pusher-sdks', [DocsController::class, 'pusherSdks'])->name('docs.guides.pusher-sdks');
Route::get('/docs/vs-reverb', [DocsController::class, 'vsReverb'])->name('docs.vs-reverb');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Authenticated dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/projects/{project}/pause', [ProjectController::class, 'pause'])->name('projects.pause');
    Route::post('/projects/{project}/resume', [ProjectController::class, 'resume'])->name('projects.resume');
    Route::post('/projects/{project}/rotate-key', [ProjectController::class, 'rotateKey'])->name('projects.rotate-key');
    Route::post('/projects/{project}/rotate-secret', [ProjectController::class, 'rotateSecret'])->name('projects.rotate-secret');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/inspector', [InspectorController::class, 'show'])->name('inspector.show');
    Route::get('/projects/{project}/inspector/channels', [InspectorController::class, 'channels'])->name('inspector.channels');
    Route::get('/projects/{project}/inspector/events/{channel}', [InspectorController::class, 'events'])->name('inspector.events');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/usage', [UsageController::class, 'index'])->name('usage');
    Route::get('/activity', [ActivityController::class, 'index'])->name('activity');

    Route::get('/webhooks', [WebhookConfigController::class, 'index'])->name('webhooks.index');
    Route::post('/webhooks', [WebhookConfigController::class, 'store'])->name('webhooks.store');
    Route::delete('/webhooks/{webhook}', [WebhookConfigController::class, 'destroy'])->name('webhooks.destroy');
    Route::post('/webhooks/{webhook}/test', [WebhookConfigController::class, 'test'])->name('webhooks.test');

    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/checkout/{plan}', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete');

    Route::get('/settings/2fa', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/settings/2fa/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::get('/settings/2fa/confirm', [TwoFactorController::class, 'confirm'])->name('two-factor.confirm');
    Route::post('/settings/2fa/confirm', [TwoFactorController::class, 'store'])->name('two-factor.store');
    Route::post('/settings/2fa/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable')->middleware('password.confirm');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/two-factor-challenge', [TwoFactorChallengeController::class, 'show'])->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [TwoFactorChallengeController::class, 'store']);
});

Route::post('/stripe/webhook', [WebhookController::class, 'handle'])->name('stripe.webhook');

require __DIR__.'/auth.php';
