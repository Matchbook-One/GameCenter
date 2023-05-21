<?php

use humhub\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);
?>
<div class="container">
  <?= $content; ?>
</div>
