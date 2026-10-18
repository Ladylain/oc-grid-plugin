<?php namespace LucasPalomba\Grid\Behaviors;

use Backend\Behaviors\ListController;
use Backend\Classes\ControllerBehavior;
use LucasPalomba\Grid\Widgets\Grids;
use LucasPalomba\Grid\Widgets\GridStructure;

/**
 * GridController displays backend records as a grid of cards instead of a table.
 *
 * It is a drop-in replacement for the ListController: the configuration file,
 * the controller overrides (listExtendQuery, listInjectRowClass, etc.), the toolbar,
 * the filter, the search, the bulk actions and the structure (reorder, tree) work
 * the same way.
 *
 * This behavior is implemented in the controller like so:
 *
 *     public $implement = [
 *         \LucasPalomba\Grid\Behaviors\GridController::class,
 *     ];
 *
 *     public $gridConfig = 'config_grid.yaml';
 *
 * The `$gridConfig` property accepts the same values as the `$listConfig` property
 * of the ListController, plus the grid options (see Grids widget). Sortable records
 * are displayed as cards, nested records (structure.showTree) as the usual table.
 *
 * @package lucaspalomba/grid
 * @author Lucas Palomba
 */
class GridController extends ListController
{
    /**
     * @var array gridFieldsToTransfer are the grid options passed from the configuration
     * to the widget, in addition to the list options.
     */
    protected $gridFieldsToTransfer = [
        'cardMinWidth',
        'cardColumns',
        'cardLabels',
        'cardLayout',
        'cardMediaRatio',
        'showSortSelector',
    ];

    /**
     * @var array requiredProperties in the controller
     */
    protected $requiredProperties = ['gridConfig'];

    /**
     * __construct the behavior
     * @param \Backend\Classes\Controller $controller
     */
    public function __construct($controller)
    {
        // The list controller constructor reads $listConfig, skip it
        ControllerBehavior::__construct($controller);

        // Extract grid definitions
        if (is_array($controller->gridConfig)) {
            $this->listDefinitions = $controller->gridConfig;
            $this->primaryDefinition = key($this->listDefinitions);
        }
        else {
            $this->listDefinitions = ['list' => $controller->gridConfig];
            $this->primaryDefinition = 'list';
        }

        // Use the list controller partials when not overridden
        $this->addViewPath($this->guessViewPathFrom(ListController::class, '/partials'), true);

        // Build configuration
        $this->setConfig($this->listDefinitions[$this->primaryDefinition], $this->requiredConfig);
    }

    /**
     * makeWidget swaps the list widgets created by the list controller for the grid widgets.
     * A tree (showTree) keeps the table of the list structure, cards do not suit nested records.
     */
    public function makeWidget($class, $widgetConfig = [])
    {
        if ($class === \Backend\Widgets\ListStructure::class && ($widgetConfig->showTree ?? true)) {
            return parent::makeWidget($class, $widgetConfig);
        }

        $gridClass = [
            \Backend\Widgets\Lists::class => Grids::class,
            \Backend\Widgets\ListStructure::class => GridStructure::class,
        ][$class] ?? null;

        if ($gridClass) {
            $class = $gridClass;

            foreach ($this->gridFieldsToTransfer as $field) {
                if (isset($this->config->{$field})) {
                    $widgetConfig->{$field} = $this->config->{$field};
                }
            }
        }

        return parent::makeWidget($class, $widgetConfig);
    }

    /**
     * gridRender is an alias of listRender
     * @param string $definition
     * @return string
     */
    public function gridRender($definition = null)
    {
        return $this->listRender($definition);
    }

    /**
     * gridRefresh is an alias of listRefresh
     * @param string $definition
     * @return array
     */
    public function gridRefresh($definition = null)
    {
        return $this->listRefresh($definition);
    }

    /**
     * gridGetWidget is an alias of listGetWidget
     * @param string $definition
     * @return \Backend\Classes\WidgetBase
     */
    public function gridGetWidget($definition = null)
    {
        return $this->listGetWidget($definition);
    }
}
