<?php

// use View;
namespace App\Providers;

use View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // if(Auth::user()->designation != "Reviewer"){
                //     $notifications = DB::select("SELECT users.id, COUNT(markmsg) AS unread FROM users LEFT JOIN notifications ON users.id = notifications.assignedto 
                //     AND notifications.markmsg = 1 WHERE users.id = " . Auth::id() . " GROUP BY users.id");
                //     $view->with('notifications', $notifications);
                // }
                // else{
                //     $notifications = DB::select("SELECT users.id, COUNT(is_read) AS unread FROM users LEFT JOIN investigated_cases ON users.id = investigated_cases.assignedto 
                //     AND investigated_cases.is_read = 1 WHERE users.id = " . Auth::id() . " GROUP BY users.id");
                //     $view->with('notifications', $notifications);
                // }
                $notifications = DB::select("SELECT users.id, COUNT(markmsg) AS unread FROM users LEFT JOIN notifications ON users.id = notifications.assignedto 
                    AND notifications.markmsg = 1 WHERE users.id = " . Auth::id() . " GROUP BY users.id");
                $view->with('notifications', $notifications);
            }
        });
    }
}
