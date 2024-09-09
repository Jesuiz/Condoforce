<?php

namespace App\Filament\Resident\Widgets;

use App\Models\User;
use App\Models\Occupation;
use App\Models\Condominium;
use App\Models\Inventory;
use App\Models\Report;
use App\Models\Task;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ResidentStats extends BaseWidget
{
    protected static bool $isLazy = false;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Nuevas Actividades', $this->getNewTasks(Auth::user()) )
                ->Icon('heroicon-o-clipboard-document-list'),
                /* ->color('success')->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('32k increase'), */

            Stat::make('Actividades Finalizadas', $this->getFinishTasks(Auth::user()) )
                ->Icon('heroicon-o-clipboard-document-list'),

            Stat::make('Nuevas Incidencias', $this->getNewReports(Auth::user()) )
                ->Icon('heroicon-o-clipboard-document-check'),

            Stat::make('Productos en Inventario', $this->getProductInventory(Auth::user()) )
                ->Icon('heroicon-o-rectangle-stack'),
        ];
    }

    protected function getNewTasks (User $user) {
        $newTasks = Task::where('user_id', $user->id)
                        ->where('status', 'Asignado')
                        ->get()->count();
        return $newTasks;
    }
    protected function getFinishTasks (User $user) {
        $finishedTasks = Task::where('user_id', $user->id)
                        ->where('status', 'Finalizado')
                        ->get()->count();
        return $finishedTasks;
    }
    protected function getNewReports (User $user) {
        $newReports = Report::where('user_id', $user->id)
                        ->get()->count();
        return $newReports;
    }
    protected function getProductInventory (User $user) {
        $productCount = Inventory::where('user_id', $user->id)
                        ->get()->count();
        return $productCount;
    }
}
