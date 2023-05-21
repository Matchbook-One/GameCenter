<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\widgets\{GameDirectoryFilters, GamesList};
use humhub\assets\CardsAsset;

/**
 * @var \humhub\modules\ui\view\components\View $this
 * @var $games
 * @var bool $showMore
 */

CardsAsset::register($this);

?>

<h1><?= GameCenterModule::t('base', 'Games'); ?></h1>

<!--<div class="panel-body">
    <?= GameDirectoryFilters::widget(); ?>
  </div>-->
<?php if (empty($games)): ?>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h1><?= GameCenterModule::t('base', 'No results found!'); ?></h1>
        </div>
        <div class="panel-body">
          <?= GameCenterModule::t('base', 'Try other keywords or remove filters.'); ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?= GamesList::widget(['games' => $games, 'showMore' => $showMore]) ?>
