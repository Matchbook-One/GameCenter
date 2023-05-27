<?php

/**
 * Events.php
 *
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter;

use fhnw\modules\gamecenter\components\{GameCenter, GameModule};
use fhnw\modules\gamecenter\helpers\Url;
use fhnw\modules\gamecenter\openapi\humhub\stubs\MenuLinkConfig;
use humhub\components\ModuleEvent;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\ui\menu\MenuLink;
use humhub\widgets\TopMenu;
use Throwable;
use Yii;
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
   * @param Event $event
   *
   * @return void
   */
  public static function onAdminMenuInit(Event $event): void
  {
    /** @var AdminMenu $sender */
    $sender = $event->sender;
    /** @var MenuLinkConfig $config */
    $config = [
        'id'        => 'admin',
        'icon'      => 'gamepad',
        'label'     => 'GameCenter',
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
      GameCenter::getInstance()
                ->register($module->id, $module);
    }
  }

  /**
   * Call before request, registering autoloader
   */
  public static function onBeforeRequest(): void
  {
    try {
      static::registerAutoloader();
    } catch (Throwable $e) {
      Yii::error($e);
    }
    Url::prepareAPIRules();
  }

  /**
   * Register composer autoloader when Reader not found
   */
  public static function registerAutoloader(): void
  {
    if (class_exists('\yii\swagger\SwaggerUIRenderer')) {
      return;
    }
    Yii::setAlias('@vendor/yii/yii2-swagger', __DIR__ . '/vendor/yii/yii2-swagger/src');
    Yii::setAlias('@bower/swagger-ui', __DIR__ . '/vendor/bower-asset/swagger-ui');

    require Yii::getAlias('@gamecenter/vendor/autoload.php');
  }

  /**
   * Defines what to do when the top menu is initialized.
   *
   * @param Event $event The Event
   *
   * @return void
   */
  public static function onTopMenuInit(Event $event): void
  {
    /** @var TopMenu $sender */
    $sender = $event->sender;
    /** @var MenuLinkConfig $config */
    $config = [
        'id'        => 'games',
        'icon'      => 'gamepad',
        'label'     => 'Games',//GameCenterModule::t('base', 'Games'),
        'url'       => Url::toGamesOverview(),
        'sortOrder' => 500,
        'isActive'  => MenuLink::isActiveState('gamecenter', 'games')
    ];
    $sender->addEntry(new MenuLink($config));
  }

}
