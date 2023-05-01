<?php

/**
 * from humhub\modules\space\widgets\views\spaceDirectoryCard.php
 */

use fhnw\modules\gamecenter\widgets\{GameDirectoryActionButtons, GameDirectoryIcons, GameDirectoryStatus, GameDirectoryTagList};
use humhub\libs\Html;

/**
 * @var      \yii\web\View                   $this
 * @var \fhnw\modules\gamecenter\models\Game $game
 */
?>

<div class="card-panel <?php if ($game->isArchived()) : ?> card-archived<?php endif; ?>">
  <div class="card-bg-image"></div>
  <div class="card-header">
    <?= GameDirectoryStatus::widget(['game' => $game]); ?>
    <div class="card-icons">
      <?= GameDirectoryIcons::widget(['game' => $game]); ?>
    </div>
  </div>
  <div class="card-body">
    <strong class="card-title"><?= Html::encode($game->title); ?></strong>
    <?php if (trim($game->description) !== '') : ?>
      <div class="card-details">
        <?= Html::encode($game->description); ?>
      </div>
    <?php endif; ?>
    <?= GameDirectoryTagList::widget(
      [
        'game'     => $game,
        'template' => '<div class="card-tags">{tags}</div>',
      ]
    ); ?>
  </div>
  <?= GameDirectoryActionButtons::widget(
    [
      'game'     => $game,
      'template' => '<div class="card-footer">{buttons}</div>',
    ]
  ); ?>
</div>
