<?php if ($showCheckboxes): ?>
    <?= $this->makePartial('list_body_checkbox', ['record' => $record]) ?>
<?php endif ?>
<?php if (!empty($showReorder)): ?>
    <?= $this->makePartial('list_body_reorder', ['record' => $record]) ?>
<?php endif ?>
<?php $index = $action = 0; foreach ($cardSections as $section => $sectionItems): ?>
    <?php if ($section === 'footer' && $sectionItems): ?>
        <td class="grid-card-divider" aria-hidden="true"></td>
    <?php endif ?>
    <?php $sectionIndex = 0; foreach ($sectionItems as $item): ?>
        <?php
            $column = $item['column'];
            $index++;
            $sectionIndex++;
            $classes = [
                'grid-section-'.$section,
                'grid-span-'.$item['span'],
                'list-cell-index-'.$index,
                'list-cell-name-'.$column->getName(),
                'list-cell-type-'.$column->type,
                $column->getAlignClass(),
                $column->cssClass
            ];

            if ($sectionIndex === 1) {
                $classes[] = 'grid-section-first';
            }

            if (!$column->clickable) {
                $classes[] = 'nolink';
            }

            $showLabel = $this->showCardLabel($column, $section);
        ?>
        <td class="<?= implode(' ', $classes) ?>">
            <?php if ($showLabel): ?>
                <span class="grid-label"><?= $this->getHeaderValue($column) ?></span>
            <?php endif ?>
            <span class="grid-value">
                <?php if ($column->clickable && !$action && ($action = $this->getRecordAction($record))): ?>
                    <a <?= $action[1] ?> href="<?= $action[0] ?>">
                        <?= $this->getColumnValue($record, $column) ?>
                    </a>
                <?php else: ?>
                    <?= $this->getColumnValue($record, $column) ?>
                <?php endif ?>
            </span>
        </td>
    <?php endforeach ?>
<?php endforeach ?>
