<?php
?>
<div class="col-lg-3" style="padding:7.5px">
    <div class="card <?= $this->getRowClass($record) ?>" <?= $this->getRecordOnClick($record) ?>>
        
        <?php 
            $groupedColumns = [];
            $otherColumns = [];
            foreach ($columns as $key => $column) {
                if ($column->group) {
                    $groupedColumns[$column->group][] = $column;
                } else {
                    $otherColumns[] = $column;
                }
            }
        ?>
        <?php if(isset($groupedColumns['header'])): ?>
        <div class="card-header d-flex flex-wrap" style="row-gap:1rem">
            <?php if ($showCheckboxes): ?>
                <?= $this->makePartial('grid_body_checkbox', ['record' => $record]) ?>
            <?php endif; ?>
            <?php $index = $action = 0; $total = count($groupedColumns['header']); 
            // on regroupe les colonnes par le parametre "group" pour les afficher dans un seul bloc
            
            foreach ($groupedColumns['header'] as $key => $column): 
                    $index++;
                    $headClasses = [
                        'list-cell-name-'.$column->getName(),
                        'list-cell-type-'.$column->type,
                        $column->getAlignClass(),
                        $column->headCssClass
                    ];
                    $classes = [
                        'list-cell-index-'.$index,
                        'list-cell-name-'.$column->getName(),
                        'list-cell-type-'.$column->type,
                        $column->getAlignClass(),
                        $column->cssClass
                    ];

                    if (!$column->clickable) {
                        $classes[] = 'nolink';
                    }
                ?>
                <div class="<?= implode(' ', $headClasses) ?>">
                    <?php if ($column->clickable && !$action && ($action = $this->getRecordAction($record))): ?>
                        <small class="text-muted" style="font-size: 10px;"><?= $this->getHeaderValue($column) ?></small><br/>
                        <a class="stretch-link <?= implode(' ', $classes) ?>" <?= $action[1] ?> href="<?= $action[0] ?>">
                            <?= $this->getColumnValue($record, $column) ?>
                        </a>
                    <?php else: ?>
                        <?php if ($column->type != 'partial'): ?>
                            <small class="text-muted" style="font-size: 10px;"><?= $this->getHeaderValue($column) ?></small><br/>
                            <span class="<?= implode(' ', $classes) ?>"><?= $this->getColumnValue($record, $column) ?></span>
                        <?php else: ?>
                            <span class="<?= implode(' ', $classes) ?>"><?= $this->getColumnValue($record, $column) ?></span>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
        <div class="card-body d-flex flex-wrap" style="row-gap:1rem">
            <?php if(!isset($groupedColumns['header'])): ?>
                <?php if ($showCheckboxes): ?>
                    <?= $this->makePartial('grid_body_checkbox', ['record' => $record]) ?>
                <?php endif; ?>
            <?php endif ?>
            <?php $index = $action = 0; $total = count($columns); 
            // on regroupe les colonnes par le parametre "group" pour les afficher dans un seul bloc
            
            foreach ($otherColumns as $key => $column): ?>
                <?php
                    $index++;
                    $headClasses = [
                        'list-cell-name-'.$column->getName(),
                        'list-cell-type-'.$column->type,
                        $column->getAlignClass(),
                        $column->headCssClass
                    ];
                    $classes = [
                        'list-cell-index-'.$index,
                        'list-cell-name-'.$column->getName(),
                        'list-cell-type-'.$column->type,
                        $column->getAlignClass(),
                        $column->cssClass
                    ];

                    if (!$column->clickable) {
                        $classes[] = 'nolink';
                    }
                ?>
                <div class="<?= implode(' ', $headClasses) ?>">
                    <?php if ($column->clickable && !$action && ($action = $this->getRecordAction($record))): ?>
                        <small class="text-muted" style="font-size: 10px;"><?= $this->getHeaderValue($column) ?></small><br/>
                        <a class="<?= implode(' ', $classes) ?>" <?= $action[1] ?> href="<?= $action[0] ?>">
                            <?= $this->getColumnValue($record, $column) ?>
                        </a>
                    <?php else: ?>
                        <?php if ($column->type != 'partial'): ?>
                            <small class="text-muted" style="font-size: 10px;"><?= $this->getHeaderValue($column) ?></small><br/>
                            <span class="<?= implode(' ', $classes) ?>"><?= $this->getColumnValue($record, $column) ?></span>
                        <?php else: ?>
                            <span class="<?= implode(' ', $classes) ?>"><?= $this->getColumnValue($record, $column) ?></span>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
        <?php if(isset($groupedColumns['footer'])): ?>
        <div class="card-footer d-flex flex-wrap" style="row-gap:1rem">
            <?php $index = $action = 0; $total = count($groupedColumns['footer']); 
            // on regroupe les colonnes par le parametre "group" pour les afficher dans un seul bloc
            
            foreach ($groupedColumns['footer'] as $key => $column): ?>
                <?php
                    $index++;
                    $headClasses = [
                        'list-cell-name-'.$column->getName(),
                        'list-cell-type-'.$column->type,
                        $column->getAlignClass(),
                        $column->headCssClass
                    ];
                    $classes = [
                        'list-cell-index-'.$index,
                        'list-cell-name-'.$column->getName(),
                        'list-cell-type-'.$column->type,
                        $column->getAlignClass(),
                        $column->cssClass
                    ];

                    if (!$column->clickable) {
                        $classes[] = 'nolink';
                    }
                ?>
                
                <div class="<?= implode(' ', $headClasses) ?>">
                    <?php if ($column->clickable && !$action && ($action = $this->getRecordAction($record))): ?>
                        <small class="text-muted" style="font-size: 10px;"><?= $this->getHeaderValue($column) ?></small><br/>
                        <a class="<?= implode(' ', $classes) ?>" <?= $action[1] ?> href="<?= $action[0] ?>">
                            <?= $this->getColumnValue($record, $column) ?>
                        </a>
                    <?php else: ?>
                        <?php if ($column->type != 'partial'): ?>
                            <small class="text-muted" style="font-size: 10px;"><?= $this->getHeaderValue($column) ?></small><br/>
                            <span class="<?= implode(' ', $classes) ?>"><?= $this->getColumnValue($record, $column) ?></span>
                        <?php else: ?>
                            <span class="<?= implode(' ', $classes) ?>"><?= $this->getColumnValue($record, $column) ?></span>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>
</div>
