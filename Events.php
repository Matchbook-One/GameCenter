<?php

/**
 * Events.php
 *
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter;

use fhnw\modules\gamecenter\components\{GameCenter, GameModule};
use humhub\components\ModuleEvent;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\ui\menu\MenuLink;
use Yii;
use yii\base\Event;
use yii\helpers\Url;

/**
 * Events
 * @phpstan-type    HtmlOptions    array{ class?: array<string, string>, style?: array<string, string> }
 * @phpstan-type    MenuLinkConfig array{ id?: string, label: string, icon: string, url: string, sortOrder: int, isActive: bool,
 *                  isVisible?: bool, htmlOptions?: HtmlOptions }
 */
class Events
{
    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param Event $event The Event
     *
     * @return void
     */
    public static function onAdminMenuInit(Event $event): void
    {
        /** @var AdminMenu $sender */
        $sender = $event->sender;
        /** @var MenuLinkConfig $config */
        $config = [
            'label'     => 'GameCenter',
            'url'       => Url::to(['/gamecenter/admin']),
            'icon'      => 'gamepad',
            'sortOrder' => 99999,
            'isActive'  => (Yii::$app->controller->module->id === 'gamecenter' && Yii::$app->controller->id == 'admin'),
        ];
        $sender->addEntry(new MenuLink($config));
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
     * @param Event $event The Event
     *
     * @return void
     */
    public static function onTopMenuInit(Event $event): void
    {
        /** @var AdminMenu $sender */
        $sender = $event->sender;
        /** @var MenuLinkConfig $config */
        $config = [
            'icon'      => 'gamepad',
            'label'     => GameCenterModule::t('base', 'GameCenter'),
            'url'       => Url::to(['/gamecenter/index']),
            'sortOrder' => 99999,
            'isActive'  => (Yii::$app->controller->module &&
                            Yii::$app->controller->module->id == 'gamecenter' &&
                            Yii::$app->controller->id == 'index')
        ];
        $sender->addEntry(new MenuLink($config));
    }
}
