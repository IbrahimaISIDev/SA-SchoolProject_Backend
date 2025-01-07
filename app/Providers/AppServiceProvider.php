<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\IAnneeRepository;
use App\Repositories\Academique\AnneeRepository;
use App\Repositories\Interfaces\ISemestreRepository;
use App\Repositories\Academique\SemestreRepository;
use App\Services\Interfaces\ISeanceService;
use App\Services\Planification\ServiceSeance;
use App\Repositories\Interfaces\ISeanceRepository;
use App\Repositories\Planification\SeanceRepository;
use App\Services\Interfaces\IServiceDisponibilite;
use App\Services\Planification\ServiceDisponibilite;
use App\Repositories\Interfaces\IDisponibiliteRepository;
use App\Repositories\Planification\DisponibiliteRepository;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAnneeRepository::class, AnneeRepository::class);
        $this->app->bind(ISemestreRepository::class, SemestreRepository::class);
        $this->app->bind(ISeanceService::class, ServiceSeance::class);
        $this->app->bind(ISeanceRepository::class, SeanceRepository::class);
        $this->app->bind(IServiceDisponibilite::class, ServiceDisponibilite::class);
        $this->app->bind(ISeanceService::class, ServiceSeance::class);
        $this->app->bind(IDisponibiliteRepository::class, DisponibiliteRepository::class);

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
