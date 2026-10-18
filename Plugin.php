<?php namespace LucasPalomba\Grid;

use System\Classes\PluginBase;

/**
 * Plugin Information File
 *
 * Grid Cards displays the backend lists as a grid of cards, see the GridController behavior.
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Grid Cards',
            'description' => __('Display the backend lists as a grid of cards'),
            'author' => 'LucasPalomba',
            'icon' => 'icon-th-large',
            'homepage' => 'https://github.com/Ladylain/oc-grid-plugin',
        ];
    }
}
