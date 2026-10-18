<?php
    $useCardLink = $this->hasRecordAction();

    $classes = [
        'list-scrollable',
    ];

    if ($useCardLink) $classes[] = 'list-rowlink';
?>
<div
    class="control-list <?= implode(' ', $classes) ?>"
    data-control="listwidget">
    <?php if (count($records)): ?>
        <?= $this->makePartial('grid_body_cards') ?>
    <?php else: ?>
        <div class="col-sm-12 no-data">
            <div class="nolink">
                <p class="no-data"><?= $noRecordsMessage ?></p>
            </div>
        </div>
    <?php endif ?>
    <?php if ($showPagination): ?>
        <div class="list-footer">
            <?php if ($showPageNumbers): ?>
                <?= $this->makePartial('grid_pagination') ?>
            <?php else: ?>
                <?= $this->makePartial('grid_pagination_simple') ?>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?= $this->makePartial('grid_datalocker') ?>
</div>
