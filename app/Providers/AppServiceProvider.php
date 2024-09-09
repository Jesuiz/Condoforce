<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Contracts\Role as RoleContract;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use App\Models\Condominium;
use App\Policies\CondominiumPolicy;
use App\Models\Inventory;
use App\Policies\InventoryPolicy;
use App\Models\Occupation;
use App\Policies\OccupationPolicy;
use App\Models\Report;
use App\Policies\ReportPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        };

        FilamentColor::register([
            'gray' => Color::Zinc,
            'orange' => Color::Orange,
            'warning' => Color::Amber,
            'yellow' => Color::Yellow,
            'lime' => Color::Lime,
            'success' => Color::Green,
            'emerald' => Color::Emerald,
            'teal' => Color::Teal,
            'cyan' => Color::Cyan,
            'sky' => Color::Sky,
            'info' => Color::Blue,
            'indigo' => Color::Indigo,
            'violet' => Color::Violet,
            'purple' => Color::Purple,
            'fuchsia' => Color::Fuchsia,
            'pink' => Color::Pink,
            'rose' => Color::Rose,
            'danger' => Color::Red,
        ]);

    }
}
