<?php

/**
 * @var \yii\web\View $this
 * @var \fhnw\modules\gamecenter\models\PlayerAchievement $achievement
 * @var bool $completed
 * @var string $title
 * @var string $description
 * @var array $iconOptions
 * @var string $icon
 * @var string $updated_at
 * @var int $progress
 * @var bool $show_progress
 */

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\widgets\AchievementProgress;
use humhub\libs\Html;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\TimeAgo;

?>
<div class=" col-lg-4 col-md-6 col-sm-12">
<div class='panel panel-primary'>
<div class="panel-heading">
  <h1 class='card-title'>
    <?= Icon::get($icon, $iconOptions) ?>
    <?= Html::encode($title) ?>
  </h1>
</div>
<div class='panel-body'>
  <?= Html::encode($description) ?>
  <?= AchievementProgress::make($progress, $show_progress) ?>
</div>
<div class='panel-footer'>
  <small>
    <?= TimeAgo::widget(
      [
        'timestamp'       => $updated_at,
        'titlePrefixInfo' => GameCenterModule::t('base', 'Updated at') . ': '
      ]
    ) ?>
  </small>
</div>
</div>
</div>