<?php namespace Waka\Wiki;

use Backend;
use Backend\Models\UserRole;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use Lang;

/**
 * wiki Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'Waka.Wutils',
    ];


    
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'waka.wiki::lang.plugin.name',
            'description' => 'waka.wiki::lang.plugin.description',
            'author'      => 'waka',
            'icon'        => 'icon-leaf'
        ];
    }

    

    /**
     * Register method, called when the plugin is first registered.
     */
    public function register(): void
    {

    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot(): void
    {
        $this->bootPackages();

    }


    public function registerWakaRules()
    {
        return [
            'asks' => [],
            'fncs' => [],
            'blocs' => [],
            'conditions' => [],
            'contents' => [
                ['\Waka\Wiki\WakaRules\Contents\Wiki'], 
            ]
        ];
    }

    /**
     * Registers any frontend components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return []; // Remove this line to activate
    }

    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));

        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();

        // Get the packages to boot
        $packages = \Config::get($pluginNamespace . '::packages');

        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                \Config::set($options['config_namespace'], $options['config']);
            }

            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    \App::register($provider);
                }
            }

            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }

    /**
     * Registers any backend permissions used by this plugin.
     */
    public function registerPermissions(): array
    {
        return []; // Remove this line to activate
    }

    /**
     * Registers backend navigation items for this plugin.
     */
    public function registerNavigation(): array
    {
        return []; // Remove this line to activate

        return [
            'wiki' => [
                'label'       => 'waka.wiki::lang.plugin.name',
                'url'         => Backend::url('waka/wiki/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['waka.wiki.*'],
                'order'       => 500,
            ],
        ];
    }
}
