<?php namespace Ladylain\Grid;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Grid',
            'description' => 'Grid Behavior for Backend Controllers',
            'author' => 'LadyLain',
            'icon' => 'icon-th'
        ];
    }

}