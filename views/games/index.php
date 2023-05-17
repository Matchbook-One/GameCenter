<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\widgets\{GameDirectoryCard, GameDirectoryFilters};
use humhub\assets\CardsAsset;
use humhub\libs\Html;

/**
 * @var \humhub\modules\ui\view\components\View                $this
 * @var \fhnw\modules\gamecenter\components\GameDirectoryQuery $games
 */

CardsAsset::register($this);
?>
<div class='panel panel-default'>

  <div class='panel-heading'>
    <?= GameCenterModule::t('base', '<strong>Games</strong>'); ?>
  </div>

  <div class="panel-body">
    <?= GameDirectoryFilters::widget(); ?>
  </div>

</div>

<div class="row cards">
  <?php if (!$games->exists()): ?>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <strong><?= GameCenterModule::t('base', 'No results found!'); ?></strong><br/>
          <?= GameCenterModule::t('base', 'Try other keywords or remove filters.'); ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php foreach ($games->all() as $game) : ?>
    <?= GameDirectoryCard::widget(['game' => $game]); ?>
  <?php endforeach; ?>
</div>

<?php if (!$games->isLastPage()) : ?>
  <?= Html::tag('div', '', [
    'class'             => 'cards-end',
    'data-current-page' => $games->pagination->getPage() + 1,
    'data-total-pages'  => $games->pagination->getPageCount(),
  ]) ?>
<?php endif; ?>
