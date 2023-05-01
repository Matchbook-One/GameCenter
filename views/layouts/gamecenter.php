<?php

use humhub\modules\ui\view\helpers\ThemeHelper;

/* @var string $content */
$container = ThemeHelper::isFluid() ? 'container-fluid' : 'container';
?>
<div class="<?= $container ?> container-cards container-spaces">
  <?= $content; ?>
</div>
