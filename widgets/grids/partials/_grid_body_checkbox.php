<div class="checkbox custom-checkbox nolabel">
    
    <input
        type="<?= ($this->alias == 'grid') ? 'checkbox' : 'radio' ?>"
        name="<?= ($this->alias == 'grid') ? 'checked[]' : 'checked' ?>"
        id="<?= $this->getId('checkbox-' . $record->getKey()) ?>"
        value="<?= $record->getKey() ?>"
        <?= $this->isRowChecked($record) ? 'checked' : '' ?>
        autocomplete="off" />
    <label class="storm-icon-pseudo" for="<?= $this->getId('checkbox-' . $record->getKey()) ?>"><?= e(trans('backend::lang.list.check')) ?></label>
</div>