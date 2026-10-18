<?php if ($showCheckboxes || $showSortSelector): ?>
    <tr class="grid-head">
        <?php if ($showCheckboxes): ?>
            <th class="list-checkbox">
                <input type="checkbox" class="form-check-input" id="<?= $this->getId('checkAll') ?>" />
                <label class="grid-check-all-label" for="<?= $this->getId('checkAll') ?>"><?= e(__('Select all')) ?></label>
            </th>
        <?php endif ?>
        <th class="grid-head-actions">
            <?php if ($showSortSelector && $sortableColumns): ?>
                <?= $this->makePartial('grid_sort_selector') ?>
            <?php endif ?>
        </th>
    </tr>
<?php endif ?>
