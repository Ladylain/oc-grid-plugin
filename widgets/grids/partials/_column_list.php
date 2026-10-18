<?php if (is_array($value)): ?>
    <ul>
    <?php foreach ( $value as $val ): ?>
        <li><?= e($val) ?></li>
    <?php endforeach ?>
    </ul>
<?php elseif (!empty($value)): ?>
    <span><?= e($value) ?></span>
<?php else: ?>
    <span>-</span>
<?php endif ?>