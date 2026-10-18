<?php namespace Ladylain\Grid\Behaviors\GridController;

/**
 * HasOverrides in the controller
 */
trait HasOverrides
{
    /**
     * gridExtendRows is called after the grid Rows are defined.
     * @param \Backend\Widgets\grid $host The hosting grid widget
     * @return void
     */
    public function gridExtendRows($host)
    {
    }

    /**
     * gridFilterExtendScopes is called after the filter scopes are defined.
     * @param \Backend\Widgets\Filter $host The hosting filter widget
     * @return void
     */
    public function gridFilterExtendScopes($host)
    {
    }

    /**
     * gridExtendModel controller override: Extend supplied model
     * @param Model $model
     * @return Model
     */
    public function gridExtendModel($model, $definition = null)
    {
        return $model;
    }

    /**
     * gridExtendQueryBefore controller override: Extend the query used for populating the grid
     * before the default query is processed.
     * @param \October\Rain\Database\Builder $query
     */
    public function gridExtendQueryBefore($query, $definition = null)
    {
    }

    /**
     * gridExtendQuery controller override: Extend the query used for populating the grid
     * after the default query is processed.
     * @param \October\Rain\Database\Builder $query
     */
    public function gridExtendQuery($query, $definition = null)
    {
    }

    /**
     * gridExtendSortRow controller override: Customize the sort Row and direction
     * to include secondary sorting Rows if necessary
     * @param \October\Rain\Database\Builder $query
     */
    public function gridExtendSortRow($query, $sortRow, $sortDirection, $definition = null)
    {
    }

    /**
     * gridExtendRecords controller override: Extend the records used for populating the grid
     * after the query is processed.
     * @param Illuminate\Contracts\Pagination\LengthAwarePaginator|Illuminate\Database\Eloquent\Collection $records
     */
    public function gridExtendRecords($records, $definition = null)
    {
    }

    /**
     * gridFilterExtendQuery controller override: Extend the query used for populating the filter
     * options before the default query is processed.
     * @param \October\Rain\Database\Builder $query
     * @param array $scope
     */
    public function gridFilterExtendQuery($query, $scope)
    {
    }

    /**
     * gridInjectCardClass returns a CSS class name for a grid Card (<tr class="...">).
     * @param  Model $record The populated model used for the Row
     * @param  string $definition grid definition (optional)
     * @return string CSS class name
     */
    public function gridInjectCardClass($record, $definition = null)
    {
    }

    /**
     * gridOverrideRowValue replaces a table Row value (<td>...</td>)
     * @param  Model $record The populated model used for the Row
     * @param  string $RowName The Row name to override
     * @param  string $definition grid definition (optional)
     * @return string HTML view
     */
    public function gridOverrideRowValue($record, $RowName, $definition = null)
    {
    }

    /**
     * gridOverrideHeaderValue replaces the entire table header contents (<th>...</th>) with custom HTML
     * @param  string $RowName The Row name to override
     * @param  string $definition grid definition (optional)
     * @return string HTML view
     */
    public function gridOverrideHeaderValue($RowName, $definition = null)
    {
    }

    /**
     * gridOverrideRecordUrl overrides the record url for the given record
     * @param \October\Rain\Database\Model $record
     * @param string|null $definition grid definition (optional)
     * @return string|array|void New url or complex directive
     */
    public function gridOverrideRecordUrl($record, $definition = null)
    {
    }

    /**
     * gridAfterReorder is called after the grid record structure is reordered
     * @param \October\Rain\Database\Model $record
     * @param string|null $definition grid definition (optional)
     */
    public function gridAfterReorder($record, $definition = null)
    {
    }

    /**
     * gridExtendRefreshResults is called when the grid is refreshed using AJAX,
     * and should return an array of additional partial updates.
     * @param Backend\Widgets\grid $host
     * @param array $result
     * @param  string $definition grid definition (optional)
     * @return array
     */
    public function gridExtendRefreshResults($host, $result, $definition = null)
    {
    }
}
