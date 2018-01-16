<?php
namespace Mtg\ShowImage;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Mtg\ShowImage\ShowPhotosController;

class ShowImageProvider extends ServiceProvider{

    public function boot(){

        $this->publishes([
            __DIR__.'/config/showImages.php' => config_path('showImages.php'),
        ]);
        $this->loadViewsFrom(__DIR__.'/views', 'showimages');

        Route::group([] ,function ($router){
            $router->any('getImages','Mtg\ShowImage\ShowPhotosController@getImages');
            $router->post('deleteImages','Mtg\ShowImage\ShowPhotosController@deleteImages');
        });
        $this->publishes([
            __DIR__.'/resources'=>public_path('vendor/showimages')
        ]);

    }
    public function register(){

    }
}