<?php

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\widgets\PanelMenu;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \fhnw\modules\gamecenter\models\Game $game */

?>
<?php if (!empty($game->getTags())) : ?>
  <div id="user-tags-panel" class="panel panel-default">

    <?= PanelMenu::widget(['id' => 'game-tags-panel']); ?>

    <div class="panel-heading">
      <?= GameCenterModule::t('base', '<strong>Game</strong> tags'); ?>
    </div>
    <div class="panel-body">
      <div class="tags">
        <?php foreach ($game->getTags() as $tag): ?>
          <?= Html::a(Html::encode($tag), Url::to(['/gamecenter/games', 'keyword' => $tag]), ['class' => 'btn btn-default btn-xs tag']); ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>
