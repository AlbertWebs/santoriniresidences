<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\FunnelController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/sitemap.xml', fn () => response()->view('sitemap')->header('Content-Type', 'application/xml'));
Route::get('/robots.txt', fn () => response(
    "User-agent: *\nAllow: /\n".(\App\Support\Seo::indexable() ? "Sitemap: ".\App\Support\Seo::url('sitemap.xml')."\n" : ''),
    200,
    ['Content-Type' => 'text/plain; charset=UTF-8']
));
Route::view('/home-2', 'home-2')->name('home-2');
Route::view('/residences', 'residences')->name('residences');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/about', 'about')->name('about');
Route::view('/insights', 'insights')->name('insights');
Route::view('/privacy-policy', 'privacy')->name('privacy');
Route::get('/testimonials', \App\Http\Controllers\TestimonialController::class)->name('testimonials');
Route::get('/enquire', [LeadController::class, 'enquire'])->name('enquire');
Route::get('/book-a-visit', [LeadController::class, 'bookVisit'])->name('visit.book');
Route::get('/go/{slug}', [FunnelController::class, 'show'])->name('funnel.show');
Route::get('/forms/{form}', [LeadController::class, 'show'])->name('forms.show');
Route::get('/documents/{document}/shared/{lead}', [Admin\DocumentController::class, 'shared'])
    ->middleware(['signed', 'throttle:30,1'])
    ->name('documents.shared')
    ->whereNumber(['document', 'lead']);

Route::middleware('throttle:8,1')->group(function () {
    Route::post('/enquire', [LeadController::class, 'storeGeneral'])->name('enquire.store');
    Route::post('/forms/{form}', [LeadController::class, 'store'])->name('leads.store');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [Admin\AuthController::class, 'show'])->name('login');
        Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::redirect('/', '/admin/website')->name('home');

        Route::get('/website', [Admin\WebsiteController::class, 'index'])->name('website.index');
        Route::get('/website/{page}', [Admin\WebsiteController::class, 'edit'])->name('website.edit');
        Route::put('/website/{page}', [Admin\WebsiteController::class, 'update'])->name('website.update');

        Route::get('/media', [Admin\MediaController::class, 'index'])->name('media.index');
        Route::get('/media/library', [Admin\MediaController::class, 'library'])->name('media.library');
        Route::post('/media/chunk', [Admin\MediaController::class, 'chunk'])->name('media.chunk');
        Route::patch('/media/{media}', [Admin\MediaController::class, 'update'])->name('media.update')->whereNumber('media');
        Route::delete('/media/{media}', [Admin\MediaController::class, 'destroy'])->name('media.destroy')->whereNumber('media');

        Route::get('/leads', [Admin\LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/visits', [Admin\LeadController::class, 'visits'])->name('leads.visits');
        Route::get('/leads/export', [Admin\LeadController::class, 'export'])->name('leads.export');
        Route::get('/leads/search', [Admin\LeadController::class, 'search'])->name('leads.search');
        Route::post('/leads/{lead}/documents', [Admin\DocumentController::class, 'attach'])->name('leads.documents.attach')->whereNumber('lead');
        Route::delete('/leads/{lead}/documents/{document}', [Admin\DocumentController::class, 'detach'])->name('leads.documents.detach')->whereNumber(['lead', 'document']);
        Route::get('/leads/{lead}', [Admin\LeadController::class, 'show'])->name('leads.show')->whereNumber('lead');
        Route::patch('/leads/{lead}', [Admin\LeadController::class, 'update'])->name('leads.update')->whereNumber('lead');
        Route::delete('/leads/{lead}', [Admin\LeadController::class, 'destroy'])->name('leads.destroy')->whereNumber('lead');

        Route::get('/forms', [Admin\LeadFormController::class, 'index'])->name('forms.index');
        Route::post('/forms', [Admin\LeadFormController::class, 'store'])->name('forms.store');
        Route::get('/forms/{form}/edit', [Admin\LeadFormController::class, 'edit'])->name('forms.edit');
        Route::put('/forms/{form}', [Admin\LeadFormController::class, 'update'])->name('forms.update');
        Route::delete('/forms/{form}', [Admin\LeadFormController::class, 'destroy'])->name('forms.destroy');

        Route::resource('funnels', Admin\FunnelController::class)->except('show');

        Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');
        Route::redirect('/projects/create', '/admin/website')->name('projects.create');
        Route::view('/profile', 'admin.profile.index')->name('profile');
        Route::get('/settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::view('/settings/backup', 'admin.settings.backup')->name('settings.backup');

        Route::prefix('cms')->name('cms.')->group(function () {
            Route::redirect('/pages', '/admin/website')->name('pages.index');
            Route::view('/pages/about', 'admin.cms.pages.about')->name('pages.about');
            Route::view('/pages/services', 'admin.cms.pages.services')->name('pages.services');
            Route::view('/pages/faqs', 'admin.cms.pages.faqs')->name('pages.faqs');
            Route::view('/blog', 'admin.cms.blog.index')->name('blog.index');
            Route::get('/testimonials', [Admin\TestimonialController::class, 'index'])->name('testimonials.index');
            Route::put('/testimonials', [Admin\TestimonialController::class, 'update'])->name('testimonials.update');
        });

        Route::prefix('leads')->name('leads.')->group(function () {
            Route::redirect('/inquiries', '/admin/leads')->name('inquiries');
            Route::redirect('/site-visits', '/admin/leads/visits')->name('site-visits');
        });

        Route::get('/legal/documents', [Admin\DocumentController::class, 'index'])->name('legal.documents');
        Route::post('/documents', [Admin\DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}', [Admin\DocumentController::class, 'show'])->name('documents.show')->whereNumber('document');
        Route::put('/documents/{document}', [Admin\DocumentController::class, 'update'])->name('documents.update')->whereNumber('document');
        Route::delete('/documents/{document}', [Admin\DocumentController::class, 'destroy'])->name('documents.destroy')->whereNumber('document');
        Route::get('/documents/{document}/download', [Admin\DocumentController::class, 'download'])->name('documents.download')->whereNumber('document');
    });
});
