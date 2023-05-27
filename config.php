<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\Events;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\components\ModuleManager;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;
use yii\base\Application;
use yii\base\Widget;

/** @var $config */
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
        ['class' => AdminMenu::class, 'event' => Widget::EVENT_INIT, 'callback' => [Events::class, 'onAdminMenuInit']],
        ['class' => TopMenu::class, 'event' => Widget::EVENT_INIT, 'callback' => [Events::class, 'onTopMenuInit']],
        ['class' => Application::class, 'event' => Application::EVENT_BEFORE_REQUEST, 'callback' => [Events::class, 'onBeforeRequest']]
    ]
];

return $config;
