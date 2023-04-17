<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\{Events, GameCenterModule};
use humhub\components\ModuleManager;
use humhub\modules\admin\widgets\AdminMenu;
use yii\base\Widget;

/**
 * @phpstan-type EventConfig  array{ class: class-string, event: string, callback: array<class-string, callable-string> }
 * @phpstan-type ModuleConfig array{ id: string, class: class-string, namespace: string, events: array<EventConfig> }
 */
$config = [
  'id'        => 'gamecenter',
  'class'     => GameCenterModule::class,
  'namespace' => 'fhnw\modules\gamecenter',
  'events'    => [
    [
      'class'    => ModuleManager::class,
      'event'    => ModuleManager::EVENT_BEFORE_MODULE_ENABLE,
      'callback' => [Events::class, 'onBeforeModuleEnabled']
    ],
    [
      'class'    => AdminMenu::class,
      'event'    => Widget::EVENT_INIT,
      'callback' => [Events::class, 'onAdminMenuInit'],
    ]
  ]
];

return $config;
