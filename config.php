<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

use fhnw\modules\gamecenter\Events;
use fhnw\modules\gamecenter\Module;
use humhub\components\ModuleManager;
use humhub\modules\admin\widgets\AdminMenu;
use yii\base\Widget;

return [
  'id'        => 'gamecenter',
  'class'     => Module::class,
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
    ],
  ],
];
