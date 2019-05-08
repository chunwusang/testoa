<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
		Route::pattern('id', '[0-9]+'); //mid
		Route::pattern('mid', '[0-9]+'); //mid
		Route::pattern('agenhid', '[0-9]+'); //
		Route::pattern('act', '[a-z0-9_]+');
		Route::pattern('cnum', '[a-z0-9_]+');
		parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapApijctRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
		
		Route::prefix('webapi')
			->middleware('webapi')
			->namespace($this->namespace)
			->group(base_path('routes/webapi.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }


    /**
     * Define the "api_jct" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApijctRoutes()
    {

        Route::prefix('apijct')
          //  ->middleware('apijct')
            ->namespace($this->namespace)
            ->group(base_path('routes/apijct.php'));

    }
}
