<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter;

use Yii;
use yii\helpers\Url;

/**
 * @Events
 * @module  GameCenter
 * @author  Christian Seiler
 * @version 1.0
 */
class Events {
  /**
   * Defines what to do when the top menu is initialized.
   *
   * @param $event
   */
  public static function onTopMenuInit($event): void {
    $config = [
      'label'     => 'GameCenter',
      'icon'      => '<i class="fa fa-gamepad"></i>',
      'url'       => Url::to(['/gamecenter/index']),
      'sortOrder' => 99999,
      'isActive'  => (
        Yii::$app->controller->module &&
        Yii::$app->controller->module->id == 'gamecenter' &&
        Yii::$app->controller->id == 'index'
      )
    ];
    $event->sender->addItem($config);
  }

  /**
   * Defines what to do if admin menu is initialized.
   *
   * @param $event
   */
  public static function onAdminMenuInit($event): void {
    $config = [
      'label'     => 'GameCenter',
      'url'       => Url::to(['/gamecenter/admin']),
      'group'     => 'manage',
      'icon'      => '<i class="fa fa-gamepad"></i>',
      'sortOrder' => 99999,
      'isActive'  => (
        Yii::$app->controller->module &&
        Yii::$app->controller->module->id == 'gamecenter' &&
        Yii::$app->controller->id == 'admin'
      )
    ];
    $event->sender->addItem($config);
  }
}
