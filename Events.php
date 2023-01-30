<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter;

use fhnw\modules\gamecenter\components\GameCenter;
use fhnw\modules\gamecenter\components\GameModule;
use humhub\components\ModuleEvent;
use Yii;
use yii\base\Event;
use yii\helpers\Url;

/**
 * Events
 */
class Events {
  /**
   * Defines what to do if admin menu is initialized.
   *
   * @param Event $event
   */
  public static function onAdminMenuInit(Event $event): void {
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

  /**
   * Registers a new Module to the GameCenter
   *
   * This function is called just before a Module is enabled
   *
   * @param ModuleEvent $event
   *
   * @return void
   */
  public static function onBeforeModuleEnabled(ModuleEvent $event): void {
    $module = $event->module;

    if ($module instanceof GameModule) {
      GameCenter::register($module->id, []);
    }
  }

  /**
   * Defines what to do when the top menu is initialized.
   *
   * @param Event $event
   *
   * @return void
   */
  public static function onTopMenuInit(Event $event): void {
    $config = [
      'icon'      => '<i class="fa fa-gamepad"></i>',
      'label'     => Yii::t('GamecenterModule.base', 'GameCenter'),
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
}
