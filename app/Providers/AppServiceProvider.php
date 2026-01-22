<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;     // Thêm dòng này để dùng DB
use Illuminate\Support\Facades\View;   // Thêm dòng này để dùng View::share
use Illuminate\Support\Facades\Schema; // Thêm dòng này để dùng Schema::hasTable

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // app/Providers/AppServiceProvider.php
    public function boot()
        {
            if (Schema::hasTable('settings')) {
                // pluck('giá_trị', 'tên_cột_làm_khóa')
                $socials = DB::table('settings')->pluck('value', 'key');
                View::share('socials', $socials);
            }
        }
}