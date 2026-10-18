<div class="list-cards row" <?= $useCardLink ? 'data-control="rowlink"' : '' ?> style="display:flex;flex-wrap: wrap;padding:10px;">
    <?php foreach ($records as $record): ?>
        <?= $this->makePartial('grid_body_card', [
            'record' => $record,
            'treeLevel' => $treeLevel
            ]) ?>
    <?php endforeach ?>
</div>