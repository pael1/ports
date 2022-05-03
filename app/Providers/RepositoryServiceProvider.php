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
        //complainant
        $this->app->bind(
            'App\Repositories\IComplaint',
            'App\Repositories\ComplaintRepository'
        );
        //comments
        $this->app->bind(
            'App\Repositories\ICommentRepository',
            'App\Repositories\CommentRepository'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
