<?php namespace LucasPalomba\Grid\Widgets;

use Backend\Widgets\Lists;
use Backend\Widgets\ListStructure;

/**
 * GridStructure widget renders a list of sortable records as cards, reordered
 * by drag and drop.
 *
 * It accepts the same configuration as the ListStructure widget, plus the options
 * described in the HasCardLayout trait. Nested records (showTree) are not displayed
 * as cards, the GridController keeps the ListStructure widget for them.
 *
 * @package lucaspalomba/grid
 * @author Lucas Palomba
 */
class GridStructure extends ListStructure
{
    use \LucasPalomba\Grid\Widgets\Grids\HasCardLayout;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        // Cards do not display a tree
        $this->showTree = false;

        $this->addCardViewPaths([ListStructure::class, Lists::class]);
        $this->initCardLayout();

        // Sorting by a column disables the reordering, hide the sort selector unless asked
        if ($this->showReorder && $this->getConfig('showSortSelector') === null) {
            $this->showSortSelector = false;
        }
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
        $this->addJs('/modules/backend/widgets/liststructure/assets/js/october.liststructure.js');
        $this->addJs('/modules/backend/widgets/lists/assets/js/october.list.js');
        $this->addCss($this->getCardStylesheetUrl());
    }

    /**
     * @inheritDoc
     */
    public function prepareVars()
    {
        parent::prepareVars();

        $this->prepareCardVars();
    }
}
