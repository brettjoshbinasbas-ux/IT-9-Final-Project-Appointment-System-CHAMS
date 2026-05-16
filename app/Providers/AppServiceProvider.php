<?php

namespace App\Providers;

use App\Models\Appointment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\URL;


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
    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
        URL::forceScheme('https');
    }
        Paginator::useBootstrapFive();

        // ─── View Composer ────────────────────────────────────────
        // Automatically pass today's appointment count to the layout
        View::composer('layouts.app', function ($view) {
            $view->with('todayCount', Appointment::today()->count());
        });

        // ─── Custom Blade Directives ──────────────────────────────

        // @statusBadge($status) — renders a colored Bootstrap badge
        Blade::directive('statusBadge', function ($status) {
            return "<?php
                \$colors = [
                    'scheduled'  => 'primary',
                    'confirmed'  => 'info',
                    'completed'  => 'success',
                    'cancelled'  => 'danger',
                    'no_show'    => 'secondary',
                ];
                \$label = \App\Models\Appointment::STATUSES[$status] ?? ucfirst($status);
                \$color = \$colors[$status] ?? 'dark';
                echo '<span class=\"badge bg-' . \$color . '\">' . \$label . '</span>';
            ?>";
        });

        // @adminOnly ... @endAdminOnly
        Blade::if('adminOnly', function () {
             /** @var User|null $user */
             $user = Auth::user();
             return Auth::check() && $user->isAdmin();
        });

    }
}
