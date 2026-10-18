<?php
    $pageNumber = $pageCurrent ?? 1;
    $activeLabel = $sortableColumns[$sortColumn] ?? null;
?>
<div class="grid-sort-selector">
    <div class="dropdown">
        <button
            type="button"
            class="btn btn-sm btn-secondary dropdown-toggle"
            data-bs-toggle="dropdown"
            aria-expanded="false">
            <?= e(__('Sort by')) ?><?php if ($activeLabel): ?>: <strong><?= e($activeLabel) ?></strong><?php endif ?>
        </button>
        <ul class="dropdown-menu">
            <?php foreach ($sortableColumns as $columnName => $label): ?>
                <li>
                    <a
                        href="javascript:;"
                        class="dropdown-item <?= $sortColumn === $columnName ? 'active' : '' ?>"
                        data-request="<?= $this->getEventHandler('onSort') ?>"
                        data-stripe-load-indicator
                        data-request-data="sortColumn: '<?= e($columnName) ?>', page: <?= (int) $pageNumber ?>"
                    ><?= e($label) ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php if ($activeLabel): ?>
        <a
            href="javascript:;"
            class="btn btn-sm btn-secondary grid-sort-direction"
            title="<?= e($sortDirection === 'asc' ? __('Ascending') : __('Descending')) ?>"
            data-request="<?= $this->getEventHandler('onSort') ?>"
            data-stripe-load-indicator
            data-request-data="sortColumn: '<?= e($sortColumn) ?>', page: <?= (int) $pageNumber ?>"
        ><i class="<?= $sortDirection === 'asc' ? 'ph ph-sort-ascending' : 'ph ph-sort-descending' ?>"></i></a>
    <?php endif ?>
</div>
