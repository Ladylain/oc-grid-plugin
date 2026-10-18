<?php namespace Ladylain\Grid\Widgets\Grids;

use Backend\Classes\ListColumn;
use October\Rain\Element\Lists\ColumnDefinition;

/**
 * IsGridElement defines all methods to satisfy the ListElement contract
 *
 * @see \October\Contracts\Element\ListElement
 * @package october\backend
 * @author Alexey Bobkov, Samuel Georges
 */
trait IsGridElement
{
    /**
     * defineColumn adds a column to the list element
     */
    public function defineColumn(string $columnName = null, string $label = null): ColumnDefinition
    {
        return $this->allColumns[$columnName] = new ListColumn([
            'columnName' => $columnName,
            'label' => $label
        ]);
    }

    /**
     * addColumnsFromModel from the model
     */
    protected function addColumnsFromModel()
    {
        if (!$this->model || !method_exists($this->model, 'defineListColumns')) {
            return;
        }

        $this->model->defineListColumns($this);
    }
}
