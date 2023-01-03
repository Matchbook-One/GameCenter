<?php
declare(strict_types=1);

use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\gamecenter\widgets\GameCenterMenu;

AdminMenu::markAsActive(['/gamecenter/admin']);

/* @var string $content */
?>

<?php $this->beginContent('@admin/views/layouts/main.php') ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <?= Yii::t('GamecenterModule.base', '<strong>Manage</strong> Games'); ?>
    </div>
    <?= GameCenterMenu::widget(); ?>
    <div class="panel-body">
      <?= $content ?>
    </div>
  </div>

<?php $this->endContent(); ?>