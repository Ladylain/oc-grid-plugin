<tr class="grid-card <?= $this->getRowClass($record) ?>">
    <?= $this->makePartial('grid_card_cells', ['record' => $record]) ?>
</tr>
