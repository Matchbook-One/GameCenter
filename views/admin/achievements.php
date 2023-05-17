<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\widgets\GameFilter;
use yii\web\View;

/* @var View $this */
?>

<h4><?= GameCenterModule::t('base', 'Achievements') ?></h4>
<div class="help-block">
  <?= GameCenterModule::t('base', 'Achievements HELP') ?>
</div>


<div class="clearfix">
  <?= GameFilter::widget() ?>
</div>
