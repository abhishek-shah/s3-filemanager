<?php
/**
 * Created by PhpStorm.
 * User: sanjogkaskar
 * Date: 3/28/19
 * Time: 4:16 PM
 */

namespace Hnrtech\Filemanager;

use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider {

    public function boot(){
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'filemanager');

        $this->publishes([
            __DIR__.'/css/' => public_path('/css/'),
        ], 'public');

        $this->publishes([
            __DIR__.'/js/' => public_path('/js/'),
        ], 'public');

        $this->publishes([
            __DIR__.'/config/path.php' => config_path('path.php'),
        ]);
    }


    public function register()
    {

    }

    public function Storage(){

    }

}