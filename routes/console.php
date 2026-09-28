<?php

use App\Models\User;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('cms:admin {email? : Administrator email} {--password= : Password to set (prompted when omitted)}', function () {
    $email = $this->argument('email') ?: env('ADMIN_EMAIL', 'admin@santoriniresidences.com');
    $password = $this->option('password') ?: $this->secret('Password for '.$email);

    if (! $password || strlen($password) < 10) {
        $this->error('The password must be at least 10 characters.');

        return 1;
    }

    $user = User::updateOrCreate(
        ['email' => $email],
        ['name' => 'Santorini Administrator', 'password' => Hash::make($password)],
    );

    $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' the CMS administrator '.$email.'.');

    return 0;
})->purpose('Create or reset the CMS administrator account');

Artisan::command('cms:demo {--purge : Remove the sample leads and funnels} {--count=600 : Number of sample leads}', function () {
    if ($this->option('purge')) {
        $this->info('Removed '.DemoDataSeeder::purge().' sample leads and the sample funnels.');

        return 0;
    }

    if (app()->isProduction() && ! $this->confirm('This adds sample leads to the production database. Continue?')) {
        return 1;
    }

    (new DemoDataSeeder)->run((int) $this->option('count'));
    $this->info('Added sample leads and funnels. Remove them with: php artisan cms:demo --purge');

    return 0;
})->purpose('Add or remove sample leads for previewing the dashboard');
