<?php namespace LucasPalomba\Grid\Widgets;

use Backend\Widgets\Lists;

/**
 * Grids widget renders a list of records as a grid of cards.
 *
 * It accepts the same configuration as the Lists widget, plus the options
 * described in the HasCardLayout trait.
 *
 * @package lucaspalomba/grid
 * @author Lucas Palomba
 */
class Grids extends Lists
{
    use \LucasPalomba\Grid\Widgets\Grids\HasCardLayout;

    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'grid';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        $this->addCardViewPaths([Lists::class]);
        $this->initCardLayout();
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
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
