<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

use fhnw\modules\gamecenter\widgets\GameCenterMenu;
use humhub\modules\admin\widgets\AdminMenu;

AdminMenu::markAsActive(['/gamecenter/admin']);

/** @var string $content */
?>

<?php $this->beginContent('@admin/views/layouts/main.php'); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
          <?= Yii::t('GamecenterModule.base', '<strong>Manage</strong> Games'); ?>
        </div>
      <?= GameCenterMenu::widget(); ?>
        <div class="panel-body">
          <?= $content ?>
        </div>
    </div>

<?php
$this->endContent();
