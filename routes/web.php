<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::view('/projects/create', 'admin.projects.create')->name('projects.create');
    Route::view('/profile', 'admin.profile.index')->name('profile');
    Route::view('/settings', 'admin.settings.index')->name('settings.index');
    Route::view('/settings/backup', 'admin.settings.backup')->name('settings.backup');

    Route::prefix('cms')->name('cms.')->group(function () {
        Route::view('/pages', 'admin.cms.pages.index')->name('pages.index');
        Route::view('/pages/about', 'admin.cms.pages.about')->name('pages.about');
        Route::view('/pages/services', 'admin.cms.pages.services')->name('pages.services');
        Route::view('/pages/faqs', 'admin.cms.pages.faqs')->name('pages.faqs');
        Route::view('/blog', 'admin.cms.blog.index')->name('blog.index');
        Route::view('/testimonials', 'admin.cms.testimonials.index')->name('testimonials.index');
    });

    Route::prefix('leads')->name('leads.')->group(function () {
        Route::view('/inquiries', 'admin.leads.inquiries')->name('inquiries');
        Route::view('/site-visits', 'admin.leads.site-visits')->name('site-visits');
    });

    Route::prefix('legal')->name('legal.')->group(function () {
        Route::view('/documents', 'admin.legal.documents')->name('documents');
    });
});
