<?php namespace LucasPalomba\Grid\Widgets\Grids;

/**
 * HasCardLayout renders the records of a list widget as cards.
 *
 * The markup keeps the table structure of the list widget (each card is a table row
 * displayed as a grid item) so the list scripts work unchanged: checkboxes, range
 * selection, toolbar bulk actions, row links and drag and drop reordering.
 *
 * Columns are placed in the card with the `cardSection` option and sized on a
 * 12 columns grid with the `cardSpan` option:
 *
 *     columns:
 *         cover:
 *             type: image
 *             cardSection: media
 *         status:
 *             type: selectable
 *             cardSection: badge
 *         title:
 *             cardSection: header
 *         price:
 *             cardSpan: half
 *         stock:
 *             cardSpan: half
 *         updated_at:
 *             cardSection: footer
 *
 * Available sections are media, badge, header, body (default) and footer.
 */
trait HasCardLayout
{
    /**
     * @var string cardMinWidth is the minimum width of a card, the grid fits as many
     * cards as possible on each line.
     */
    public $cardMinWidth = '280px';

    /**
     * @var int|null cardColumns forces a number of cards per line on large screens.
     */
    public $cardColumns = null;

    /**
     * @var bool cardLabels displays the column labels in the card body.
     */
    public $cardLabels = true;

    /**
     * @var string cardLayout is the card design: default (media above the header)
     * or overlay (header displayed over the media).
     */
    public $cardLayout = 'default';

    /**
     * @var string|null cardMediaRatio is the aspect ratio of the media, for example 1 / 1.
     */
    public $cardMediaRatio = null;

    /**
     * @var bool showSortSelector displays the sort selector above the cards.
     */
    public $showSortSelector = true;

    /**
     * @var array cardSections are the available card sections, in display order.
     */
    protected $cardSections = ['media', 'badge', 'header', 'body', 'footer'];

    /**
     * @var array cardSpanNames are the named widths of the cardSpan option.
     */
    protected $cardSpanNames = [
        'full' => 12,
        'three-quarters' => 9,
        'two-thirds' => 8,
        'half' => 6,
        'third' => 4,
        'quarter' => 3,
    ];

    /**
     * initCardLayout
     */
    protected function initCardLayout()
    {
        $this->fillFromConfig([
            'cardMinWidth',
            'cardColumns',
            'cardLabels',
            'cardLayout',
            'cardMediaRatio',
            'showSortSelector',
        ]);

        $this->cssClasses[] = 'grid-widget';
    }

    /**
     * prepareCardVars for display
     */
    protected function prepareCardVars()
    {
        $this->vars['cardStyle'] = $this->getCardStyle();
        $this->vars['cardLayout'] = $this->cardLayout === 'overlay' ? 'overlay' : 'default';
        $this->vars['showSortSelector'] = $this->showSorting && $this->showSortSelector;
        $this->vars['sortableColumns'] = $this->getCardSortableColumns();
        $this->vars['cardSections'] = $this->getCardSections();
    }

    /**
     * getCardSections returns the visible columns grouped by card section,
     * each item containing the column and its width on the 12 columns grid.
     * @return array
     */
    public function getCardSections(): array
    {
        $sections = array_fill_keys($this->cardSections, []);

        foreach ($this->getVisibleColumns() as $name => $column) {
            $section = $column->cardSection ?: 'body';
            if (!array_key_exists($section, $sections)) {
                $section = 'body';
            }

            $sections[$section][$name] = ['column' => $column, 'span' => null];
        }

        foreach ($sections as $section => &$items) {
            foreach ($items as &$item) {
                $item['span'] = $this->getCardSpan($item['column'], $section, count($items));
            }
        }

        return $sections;
    }

    /**
     * getCardSpan returns the width of a column on the 12 columns grid of the card.
     */
    protected function getCardSpan($column, string $section, int $sectionCount): int
    {
        $span = $column->cardSpan;

        // Support the col-* classes of Bootstrap in the head CSS class
        if ($span === null && preg_match('/\bcol-(\d{1,2})\b/', (string) $column->headCssClass, $matches)) {
            $span = (int) $matches[1];
        }

        if (is_string($span) && isset($this->cardSpanNames[$span])) {
            $span = $this->cardSpanNames[$span];
        }

        if (is_numeric($span)) {
            return max(1, min(12, (int) $span));
        }

        // Footer columns share the line, long values use the full width
        if ($section === 'footer') {
            return max(3, intdiv(12, max(1, $sectionCount)));
        }

        if ($section === 'body' && !in_array($column->type, ['summary', 'partial', 'image', 'file'])) {
            return 6;
        }

        return 12;
    }

    /**
     * getCardSortableColumns returns the columns available in the sort selector.
     * @return array
     */
    protected function getCardSortableColumns(): array
    {
        $result = [];

        foreach ($this->getVisibleColumns() as $name => $column) {
            if ($column->sortable) {
                $result[$name] = $this->getHeaderValue($column);
            }
        }

        return $result;
    }

    /**
     * getCardStyle returns the CSS variables used by the grid layout.
     * @return string
     */
    protected function getCardStyle(): string
    {
        $styles = ['--grid-card-min-width:'.e($this->cardMinWidth)];

        if ($this->cardColumns) {
            $styles[] = '--grid-card-columns:'.(int) $this->cardColumns;
        }

        if ($this->cardMediaRatio && preg_match('/^\d+(\.\d+)?\s*(\/\s*\d+(\.\d+)?)?$/', (string) $this->cardMediaRatio)) {
            $styles[] = '--grid-media-ratio:'.$this->cardMediaRatio;
        }

        return implode(';', $styles);
    }

    /**
     * showCardLabel returns true when the column label is displayed in the card.
     */
    public function showCardLabel($column, string $section): bool
    {
        if ($column->cardLabel !== null) {
            return (bool) $column->cardLabel;
        }

        return $section === 'body' && $this->cardLabels;
    }

    /**
     * getCardStylesheetUrl returns the URL of the grid stylesheet, versioned with its
     * modification time so browsers load the new styles after an update.
     */
    protected function getCardStylesheetUrl(): string
    {
        $file = '/css/grid.css';
        $path = $this->guessViewPathFrom(\LucasPalomba\Grid\Widgets\Grids::class, '/assets').$file;
        $url = $this->guessViewPathFrom(\LucasPalomba\Grid\Widgets\Grids::class, '/assets', true).$file;

        return is_file($path) ? $url.'?v='.filemtime($path) : $url;
    }

    /**
     * addCardViewPaths makes the grid partials override the list partials.
     * @param array $classes List widget classes to fall back on, most specific first.
     */
    protected function addCardViewPaths(array $classes)
    {
        $paths = [$this->guessViewPathFrom(\LucasPalomba\Grid\Widgets\Grids::class, '/partials')];

        foreach ($classes as $class) {
            $paths[] = $this->guessViewPathFrom($class, '/partials');
        }

        $this->viewPath = array_values(array_unique(array_merge((array) $this->viewPath, $paths)));
    }
}
