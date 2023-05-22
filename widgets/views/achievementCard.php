<?php

/**
 * @var      \yii\web\View $this
 * @var \fhnw\modules\gamecenter\models\PlayerAchievement $achievement
 */

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\widgets\AchievementProgress;
use humhub\libs\Html;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\TimeAgo;

$iconOptions = [
  'tooltip'     => $achievement->isCompleted() ? 'unlocked' : 'locked',
  'htmlOptions' => ['class' => 'img-fluid rounded-start']
]
?>
<div class=" col-lg-4 col-md-6 col-sm-12">
<div class='panel panel-primary'>
<div class="panel-heading">
  <h1 class='card-title'>
    <?= Icon::get($achievement->isCompleted() ? 'glass' : 'lock', $iconOptions) ?>
    <?php if ($achievement->achievement->secret): ?>
      <?= GameCenterModule::t('achievement', 'Secret Achievement') ?>
    <?php else: ?>
      <?= Html::encode($achievement->achievement->title) ?>
    <?php endif; ?>
  </h1>
</div>
<div class='panel-body'>
  <?php if ($achievement->achievement->secret): ?>
    <?= GameCenterModule::t('achievement', 'This Achievement is hidden') ?>
  <?php else: ?>
    <?= Html::encode($achievement->achievement->description) ?>
  <?php endif; ?>
  <?= AchievementProgress::make($achievement->percent_completed, $achievement->achievement->show_progress) ?>
</div>
<div class='panel-footer'>
  <small>
    <?= TimeAgo::widget(
      [
        'timestamp'       => $achievement->updated_at,
        'titlePrefixInfo' => GameCenterModule::t('base', 'Updated at') . ': '
      ]
    ) ?>
  </small>
</div>
</div>
</div>