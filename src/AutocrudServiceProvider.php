<?php
namespace TianRosandhy\Autocrud;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class AutocrudServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(realpath(__DIR__ . "/../database/migrations"));

        // publish the config
        $this->publishes([
            __DIR__ . '/../config/autocrud.php' => config_path('autocrud.php'),
            __DIR__ . '/../assets' => public_path('autocrud-assets'),
        ]);

        // boot blade component
        Blade::componentNamespace('TianRosandhy\Autocrud\Components\Input', 'autocrud-input');
    }

    public function register()
    {
        // load helper
        $this->loadHelper();

        // handle package default config
        $this->mergeConfigFrom(
            __DIR__.'/../config/autocrud.php', 'autocrud'
        );

        // handle routing
        $this->routeMapping($this->app->router);

        // handle package default views
        $this->loadViewsFrom(__DIR__ . "/Resources/Views", 'autocrud');

        // handle facade to service alias
        $aliases = [
            'Autocrud' => \TianRosandhy\Autocrud\Autocrud::class,
            'Input' => \TianRosandhy\Autocrud\Components\Input::class,
            'Media' => \TianRosandhy\Autocrud\Facades\Media::class,
            'DatatableStructure' => \TianRosandhy\Autocrud\Facades\DatatableStructure::class,
            'ExportStructure' => \TianRosandhy\Autocrud\Facades\ExportStructure::class,
            'FormStructure' => \TianRosandhy\Autocrud\Facades\FormStructure::class,
            'SlugMaster' => \TianRosandhy\Autocrud\Facades\SlugMaster::class,
        ];
        foreach ($aliases as $key => $class) {
            AliasLoader::getInstance()->alias($key, $class);
        }
    }

    public function loadHelper()
    {
        require_once(__DIR__.'/helper.php');
    }

    protected function routeMapping($router)
    {
        $router->group([
            'prefix' => 'autocrud'
        ], function ($router) {
            require realpath(__DIR__ . "/Http/Routes/web.php");
        });
    }
}