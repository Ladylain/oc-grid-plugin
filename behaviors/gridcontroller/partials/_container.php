<?php if ($toolbar): ?>
    <?= $toolbar->render() ?>
<?php endif ?>

<div class="grid-widget-container">
    <?= $grid->render($filter) ?>
</div>
