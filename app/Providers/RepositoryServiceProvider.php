<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //user
        $this->app->bind(
          'App\Http\Controllers\Interfaces\User\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\AuthRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\HomeRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\HomeRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\OrdersRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\OrdersRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\User\UserRepositoryInterface',
            'App\Http\Controllers\Eloquent\User\UserRepository'
        );

        //worker
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Worker\AuthRepositoryInterface',
            'App\Http\Controllers\Eloquent\Worker\AuthRepository'
        );

        $this->app->bind(
            'App\Http\Controllers\Interfaces\Worker\OrderRepositoryInterface',
            'App\Http\Controllers\Eloquent\Worker\OrderRepository'
        );

        $this->app->bind(
            'App\Http\Controllers\Interfaces\Worker\WorkerRepositoryInterface',
            'App\Http\Controllers\Eloquent\Worker\WorkerRepository'
        );

        //admin
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\HomeRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\HomeRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\UserRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\UserRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\OrderRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\OrderRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\CategoryRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\CategoryRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\WorkerRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\WorkerRepository'
        );
        $this->app->bind(
            'App\Http\Controllers\Interfaces\Admin\AdminRepositoryInterface',
            'App\Http\Controllers\Eloquent\Admin\AdminRepository'
        );

        //App
        $this->app->bind(
            'App\Http\Controllers\Interfaces\App\AppRepositoryInterface',
            'App\Http\Controllers\Eloquent\App\AppRepository'
        );
    }
}
