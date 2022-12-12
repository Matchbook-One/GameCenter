<?php

namespace  humhub\modules\fhnw\gamecenter;

use Yii;
use yii\helpers\Url;

class Events
{
    /**
     * Defines what to do when the top menu is initialized.
     *
     * @param $event
     */
    public static function onTopMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Gamecenter',
            'icon' => '<i class="fa fa-gamepad"></i>',
            'url' => Url::to(['/gamecenter/index']),
            'sortOrder' => 99999,
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'gamecenter' && Yii::$app->controller->id == 'index'),
        ]);
    }

    /**
     * Defines what to do if admin menu is initialized.
     *
     * @param $event
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem([
            'label' => 'Gamecenter',
            'url' => Url::to(['/gamecenter/admin']),
            'group' => 'manage',
            'icon' => '<i class="fa fa-gamepad"></i>',
            'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'gamecenter' && Yii::$app->controller->id == 'admin'),
            'sortOrder' => 99999,
        ]);
    }
}
