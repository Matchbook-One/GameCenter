<?php

/**
 * from humhub\modules\space\widgets\views\spaceDirectoryCard.php
 */

use fhnw\modules\gamecenter\widgets\GameDirectoryActionButtons;
use humhub\libs\Html;
use humhub\modules\ui\icon\widgets\Icon;

/**
 * @var      \yii\web\View                   $this
 * @var \fhnw\modules\gamecenter\models\Game $game
 */
?>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h1 class="panel-title"><?= Html::encode($game->title); ?></h1>
  </div>
  <div class="panel-body">
    <p class="text-right">
      <?= Icon::get('users') ?>
      <?= $game->getPlayers()
               ->count() ?>
    </p>
    <?php if (trim($game->description) !== '') : ?>
      <div class="card-details">
        <?= Html::encode($game->description); ?>
      </div>
    <?php endif; ?>
    <div class='card-tags'>
      <?php foreach ($game->gameTags as $tag): ?>
        <span class='label label-default'><?= $tag->tag ?></span>
      <?php endforeach ?>
    </div>
  </div>
  <div class="panel-footer">
    <?= GameDirectoryActionButtons::widget(['game' => $game]); ?>
  </div>
</div>
