<tr
    class="grid-card <?= $this->getRowClass($record) ?>"
    data-tree-id="<?= $record->getKey() ?>"
    data-tree-root-id="<?= $record->site_root_id ?: $record->getKey() ?>"
    data-tree-level="0"
    data-tree-expanded="false"
    data-tree-children="0"
>
    <?= $this->makePartial('grid_card_cells', ['record' => $record]) ?>
</tr>
