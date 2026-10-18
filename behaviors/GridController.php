<?php namespace Ladylain\Grid\Behaviors;

use Lang;
use Event;
use Flash;
use BackendAuth;
use ApplicationException;
use Backend\Classes\ControllerBehavior;
use ForbiddenException;

/**
 * GridController adds features for working with backend lists
 * 
 * This behavior is implemented in the controller like so: 
 * 
 *     public $implement = [
 *        \Ladylain\Grid\Behaviors\GridController::class,
 *    ];
 * 
 *   public $gridConfig = 'config_grid.yaml';
 * 
 * The `$gridConfig` property makes reference to the grid configuration
 * values as either a YAML file, located in the controller view directory,
 * or directly as a PHP array.
 * 
 * @package ladylain/grid
 * @author Lucas Palomba
 * 
 * This plugin is based on the OctoberCMS plugin "Backend.Behaviors.ListController"
 */
class GridController extends ControllerBehavior
{
    use \Ladylain\Grid\Behaviors\GridController\HasOverrides;

    /**
     * @var array gridDefinitions are keys for alias and value for configuration
     */
    protected $gridDefinitions;

    /**
     * @var array primaryDefinition grid alias to use. Default: grid
     */
    protected $primaryDefinition;

    /**
     * @var array gridConfig are keys for alias and value for config objects.
     */
    protected $gridConfig = [];

    /**
     * @var \Backend\Classes\WidgetBase[] gridWidgets reference to the grid widget object.
     */
    protected $gridWidgets = [];

        /**
     * @var \Backend\Classes\WidgetBase[] toolbarWidgets reference to the toolbar widget objects.
     */
    protected $toolbarWidgets = [];

    /**
     * @var \Backend\Classes\WidgetBase[] filterWidgets reference to the filter widget objects.
     */
    protected $filterWidgets = [];

    /**
     * @var array requiredProperties in the controller
     */
    protected $requiredProperties = ['gridConfig'];

        /**
     * @var array requiredConfig values that must exist when applying the primary config file.
     * - modelClass: Class name for the model
     * - grid: Grid row definitions
     */
    protected $requiredConfig = ['modelClass', 'grid'];

    /**
     * @var array actions visible in context of the controller
     */
    protected $actions = ['index'];

    /**
     * __contruct the behavior
     * @param \Backend\Classes\Controller $controller
     */
    public function __construct($controller)
    {
        parent::__construct($controller);

        if(is_array($controller->gridConfig)) {
            $this->gridDefinitions = $controller->gridConfig;
            $this->primaryDefinition = key($this->gridDefinitions);
        }
        else {
            $this->gridDefinitions = ['grid' => $controller->gridConfig];
            $this->primaryDefinition = 'grid';
        }

        $this->setConfig($this->gridDefinitions[$this->primaryDefinition], $this->requiredConfig);
    }

    /**
     * makeGrids creates all the list widgets based on the definitions.
     * @return array
     */
    public function makeGrids()
    {
        foreach ($this->gridDefinitions as $definition => $config) {
            $this->gridWidgets[$definition] = $this->makeGrid($definition);
        }

        return $this->gridWidgets;
    }

    /**
     * makeGrid prepares the widgets used by this action
     * @return \Backend\Classes\WidgetBase
     */
    public function makeGrid($definition = null)
    {
        if (!$definition || !isset($this->gridDefinitions[$definition])) {
            $definition = $this->primaryDefinition;
        }

        $gridConfig = $this->config = $this->controller->gridGetConfig($definition);

        // create de model
        $model = $this->createModel();
        $model = $this->controller->gridExtendModel($model, $definition);

        // prepare the grid widget
        $widgetConfig = $this->makeConfig($gridConfig->grid);
        $widgetConfig->model = $model;
        $widgetConfig->alias = $gridConfig->widgetAlias ?? $definition;

        // Prepare the cards configuration
        $configFieldsToTransfer = [
            'recordUrl',
            'recordOnClick',
            'recordsPerPage',
            'perPageOptions',
            'showPageNumbers',
            'noRecordsMessage',
            'defaultSort',
            'showSorting',
            'showSetup',
            'showCheckboxes',
            'customViewPath',
            'customPageName'
        ];

        foreach ($configFieldsToTransfer as $field) {
            if (isset($gridConfig->{$field})) {
                $widgetConfig->{$field} = $gridConfig->{$field};
            }
        }

        // Grid Widget with extensibility
        $structureConfig = $this->makeGridStructureConfig($widgetConfig, $gridConfig);
        if ($structureConfig) {
             $widget = $this->makeWidget(\Ladylain\Grid\Widgets\GridStructure::class, $structureConfig);
        }
        else {
            $widget = $this->makeWidget(\Ladylain\Grid\Widgets\Grids::class, $widgetConfig);
        }

        $widget->bindEvent('grid.extendRows', function () use ($widget) {
            $this->controller->gridExtendRows($widget);
        });

        $widget->bindEvent('grid.extendQueryBefore', function ($query) use ($definition) {
            $this->controller->gridExtendQueryBefore($query, $definition);
        });

        $widget->bindEvent('grid.extendQuery', function ($query) use ($definition) {
            $this->controller->gridExtendQuery($query, $definition);
        });

        $widget->bindEvent('grid.extendSortRow', function ($query, $sortColumn, $sortDirection) use ($definition) {
            $this->controller->gridExtendRowCard($query, $sortColumn, $sortDirection, $definition);
        });

        $widget->bindEvent('grid.extendRecords', function ($records) use ($definition) {
            $this->controller->gridExtendRecords($records, $definition);
        });

        $widget->bindEvent('grid.injectCardClass', function ($record) use ($definition) {
            return $this->controller->gridInjectCardClass($record, $definition);
        });

        $widget->bindEvent('grid.overrideRowValue', function ($record, $row, $value) use ($definition) {
            return $this->controller->gridOverrideRowValue($record, $row->rowName, $definition);
        });

        $widget->bindEvent('grid.overrideRowLabel', function ($row, $value) use ($definition) {
            return $this->controller->gridOverrideRowLabel($row->rowName, $definition);
        });

        $widget->bindEvent('grid.overrideRecordAction', function ($record, $url, $onClick) use ($definition) {
            return $this->controller->gridOverrideRecordUrl($record, $definition);
        });

        $widget->bindEvent('grid.reorderStructure', function ($record) use ($definition) {
            return $this->controller->gridAfterReorder($record, $definition);
        });

        $widget->bindEvent('grid.refresh', function ($result) use ($widget, $definition) {
            return $this->controller->gridExtendRefreshResults($widget, $result, $definition);
        });

        $widget->bindToController();

        // Prepare the toolbar widget (optional)

        if (isset($gridConfig->toolbar)) {
            $toolbarConfig = $this->makeConfig($gridConfig->toolbar);
            $toolbarConfig->alias = $widget->alias . 'Toolbar';
            $toolbarWidget = $this->makeWidget(\Backend\Widgets\Toolbar::class, $toolbarConfig);
            $toolbarWidget->gridWidgetId = $widget->getId();
            $toolbarWidget->cssClasses[] = 'list-header';

            // Pass the list setup AJAX handler to the toolbar
            if (isset($gridConfig->showSetup) && $gridConfig->showSetup) {
                $toolbarWidget->setupHandler = $widget->getEventHandler('onLoadSetup');
            }

            // Link the search widget to the Grid Widget
            if ($searchWidget = $toolbarWidget->getSearchWidget()) {
                $searchWidget->bindEvent('search.submit', function () use ($widget, $searchWidget) {
                    $widget->setSearchTerm($searchWidget->getActiveTerm(), true);
                    return $widget->onRefresh();
                });

                $widget->setSearchOptions([
                    'mode' => $searchWidget->mode,
                    'scope' => $searchWidget->scope,
                ]);

                $widget->setSearchTerm($searchWidget->getActiveTerm());
                   
            }

            $toolbarWidget->bindToController();

            $this->toolbarWidgets[$definition] = $toolbarWidget;
        }

        // Prepare the filter widget (optional)
        if (isset($gridConfig->filter)) {
            $widget->cssClasses[] = 'list-flush';

            $filterConfig = $this->makeConfig($gridConfig->filter);
            $filterConfig->model = $model;
            $filterConfig->alias = $widget->alias . 'Filter';
            $filterConfig->customPageName = $gridConfig->customPageName ?? null;
            $filterWidget = $this->makeWidget(\Backend\Widgets\Filter::class, $filterConfig);

            // Filter tje grid when the scope are changed
            $filterWidget->bindEvent('filter.update', function () use ($widget) {
                return $widget->onFilter();
            });

            $filterWidget->bindEvent('filter.extendScopes', function () use ($filterWidget) {
                return $this->controller->gridFilterExtendScopes($filterWidget);
            });

            // Extend the query of the grid of options
            $filterWidget->bindEvent('filter.extendQuery', function($query, $scope) {
                $this->controller->gridFilterExtendQuery($query, $scope);
            });

            // Apply predefined filter values
            $widget->addFilter([$filterWidget, 'applyAllScopesToQuery']);

            // Bind to controller
            $filterWidget->bindToController();

            $this->filterWidgets[$definition] = $filterWidget;
        }

        return $widget;
    }

    // /**
    //  * makeGridStructureConfig
    //  */
    protected function makeGridStructureConfig(object $widgetConfig, object $config): ?object
    {
        if (isset($config->structure)) {
            return $this->mergeConfig($widgetConfig, $config->structure);
        }

        return null;
    }

    /**
     * index controller action
     * @return void
     */
    public function index()
    {
        if (!$this->controller->pageTitle) {
            $this->controller->pageTitle = Lang::get($this->getConfig(
                'title',
                'backend::lang.list.default_title'
            ));
        }

        $this->controller->bodyClass = 'slim-container';

        $this->makeGrids();
    }
    

    /**
     * gridGetConfig returns the configuration used by this behavior. You may override this
     * method in your controller as an alternative to defining a gridConfig property.
     * @return object|null
     */
    public function gridGetConfig($definition = null)
    {
        if (!$definition) {
            $definition = $this->primaryDefinition;
        }

        $config = array_get($this->gridConfig, $definition);

        if (!$config) {
            $config = $this->gridConfig[$definition] = $this->makeConfig($this->gridDefinitions[$definition], $this->requiredConfig);
        }

        return $config;
    }

    /**
     * gridRender renders the widget collection.
     * @param  string $definition Optional grid definition.
     * @return string Rendered HTML for the grid.
     */
    public function gridRender($definition = null)
    {
        if (!count($this->gridWidgets)) {
            throw new ApplicationException(Lang::get('backend::lang.grid.behavior_not_ready'));
        }

        if (!$definition || !isset($this->gridDefinitions[$definition])) {
            $definition = $this->primaryDefinition;
        }

        $vars = [
            'toolbar' => null,
            'filter' => null,
            'grid' => null,
        ];

        if (isset($this->toolbarWidgets[$definition])) {
            $vars['toolbar'] = $this->toolbarWidgets[$definition];
        }

        if (isset($this->filterWidgets[$definition])) {
            $vars['filter'] = $this->filterWidgets[$definition];
        }

        $vars['grid'] = $this->gridWidgets[$definition];

        return $this->gridMakePartial('container', $vars);
    }

    /**
     * gridMakePartial is a controller accessor for making partials within this behavior.
     * @param string $partial
     * @param array $params
     * @return string Partial contents
     */
    public function gridMakePartial($partial, $params = [])
    {
        $contents = $this->controller->makePartial('grid_'.$partial, $params + $this->vars, false);
        if (!$contents) {
            $contents = $this->makePartial($partial, $params);
        }

        return $contents;
    }

    /**
     * createModel is an internal method used to prepare the grid model object.
     * @return October\Rain\Database\Model
     */
    protected function createModel()
    {
        $class = $this->config->modelClass;
        return new $class;
    }

}
