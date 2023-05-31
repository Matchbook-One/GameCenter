<?php

use fhnw\modules\gamecenter\assets\GameCenterAssets;
use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\helpers\Url;
use fhnw\modules\gamecenter\widgets\GameCard;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\helpers\ContentContainerHelper;
use humhub\widgets\Button;

GameCenterAssets::register($this);

/**
 * @var \fhnw\modules\gamecenter\components\GameDirectoryQuery $games
 * @var bool                                                   $showMore
 */

/* @var ContentContainerActiveRecord $container */
$container = ContentContainerHelper::getCurrent();
?>

<div id="game-list" class="row">

  <?php foreach ($games as $game): ?>
    <div class="col-lg-4 col-md-6 col-sm-12">
      <?= GameCard::widget(['game' => $game]) ?>
    </div>
  <?php endforeach; ?>
</div>

<?php if ($showMore) : ?>
  <?php $showMoreUrl = is_string($showMore) ? $showMore : Url::toLoadGamePage($container) ?>
  <div style="text-align:center">
    <?= Button::primary(GameCenterModule::t('base', 'Show more'))
              ->action('games.showMore', $showMoreUrl) ?>
  </div>
<?php endif; ?>
