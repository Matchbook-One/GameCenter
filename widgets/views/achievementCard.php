<?php

/**
 * @var      \yii\web\View $this
 * @var \fhnw\modules\gamecenter\models\PlayerAchievement $achievement
 */

use humhub\libs\Html;
use humhub\modules\ui\icon\widgets\Icon;

$iconOptions = [
  'tooltip'     => $achievement->isCompleted() ? 'unlocked' : 'locked',
  'htmlOptions' => ['class' => 'img-fluid rounded-start']
]
?>

<div class='panel panel-primary'>
  <div class="panel-heading">
    <h1 class='card-title'>
      <?= Icon::get($achievement->isCompleted() ? 'glass' : 'lock', $iconOptions) ?>
      <?= Html::encode($achievement->achievement->title) ?>
    </h1>
  </div>
  <div class='panel-body'>
    <?= Html::encode($achievement->achievement->description) ?>
  </div>
  <div class='panel-footer'>
    <small class='text-body-secondary'>Last updated 3 mins ago</small>
  </div>
</div>
