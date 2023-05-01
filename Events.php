<?php

/**
 * Events.php
 *
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter;

use fhnw\modules\gamecenter\components\{GameCenter, GameModule};
use humhub\components\ModuleEvent;
use humhub\modules\ui\menu\MenuLink;
use yii\base\Event;

/**
 * Events
 *
 * @package GameCenter
 */
class Events
{
  /**
   * Defines what to do if admin menu is initialized.
   *
   * @param \yii\base\Event $event
   *
   * @return void
   */
  public static function onAdminMenuInit(Event $event): void
  {
    /** @var \humhub\modules\admin\widgets\AdminMenu $sender */
    $sender = $event->sender;
    /** @var \fhnw\humhub\stubs\MenuLinkConfig $config */
    $config = [
      'id'        => 'admin',
      'icon'      => 'gamepad',
      'label'     => GameCenterModule::t('base', 'GameCenter'),
      'url'       => ['/gamecenter/admin'],
      'sortOrder' => 450,
      'isActive'  => MenuLink::isActiveState('gamecenter', 'admin')
    ];
    //$sender->addEntry(new MenuLink($config));
  }

  /**
   * Registers a new Module to the GameCenter
   * This function is called just before a Module is enabled
   *
   * @param ModuleEvent $event The Module Event
   *
   * @return void
   */
  public static function onBeforeModuleEnabled(ModuleEvent $event): void
  {
    $module = $event->module;

    if ($module instanceof GameModule) {
      GameCenter::getInstance()->register($module->id, $module);
    }
  }

  /**
   * Defines what to do when the top menu is initialized.
   *
   * @param \yii\base\Event $event The Event
   *
   * @return void
   */
  public static function onTopMenuInit(Event $event): void
  {
    /** @var \humhub\widgets\TopMenu $sender */
    $sender = $event->sender;
    /** @var \fhnw\humhub\stubs\MenuLinkConfig $config */
    $config = [
      'id'        => 'games',
      'icon'      => 'gamepad',
      'label'     => 'Games',//GameCenterModule::t('base', 'Games'),
      'url'       => ['/gamecenter/games'],
      'sortOrder' => 500,
      'isActive'  => MenuLink::isActiveState('gamecenter', 'games')
    ];
    $sender->addEntry(new MenuLink($config));
  }
}
