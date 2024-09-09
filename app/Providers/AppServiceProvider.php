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

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        };
        

        // Register the Policy for every Model
        $this->app->bind(RoleContract::class, Role::class);
        $this->app->bind('filament.role.policy', \App\Policies\RolePolicy::class);

        $this->app->bind(PermissionContract::class, Permission::class);
        $this->app->bind('filament.permission.policy', \App\Policies\PermissionPolicy::class);


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


/* 
        $this->app->bind(Condominium::class, Condominium::class);
        $this->app->bind('filament.condominium.policy', \App\Policies\CondominiumPolicy::class);

        $this->app->bind(Inventory::class, Inventory::class);
        $this->app->bind('filament.inventory.policy', \App\Policies\InventoryPolicy::class);

        $this->app->bind(Occupation::class, Occupation::class);
        $this->app->bind('filament.occupation.policy', \App\Policies\OccupationPolicy::class);

        $this->app->bind(Report::class, Report::class);
        $this->app->bind('filament.report.policy', \App\Policies\ReportPolicy::class);

        $this->app->bind(Task::class, Task::class);
        $this->app->bind('filament.task.policy', \App\Policies\TaskPolicy::class); */