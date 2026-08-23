<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // @canPermission('permission.key') ... @endCanPermission
        Blade::directive('canPermission', function (string $expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endCanPermission', function () {
            return '<?php endif; ?>';
        });

        // @cannotPermission('permission.key') ... @endCannotPermission
        Blade::directive('cannotPermission', function (string $expression) {
            return "<?php if(!auth()->check() || !auth()->user()->hasPermission({$expression})): ?>";
        });

        Blade::directive('endCannotPermission', function () {
            return '<?php endif; ?>';
        });
    }
}
