<?php

/**
 * @package GameCenter
 * @since 1.0.0
 * @author  Christian Seiler <christian@christianseiler.ch>
 */

namespace fhnw\modules\gamecenter\notifications;

use humhub\modules\notification\components\BaseNotification;
use yii\helpers\Html;

/**
 * Notifies a user about something happend
 */
class AchievementUnlocked extends BaseNotification
{
    // Module Id (required)
    public $moduleId = "gamecenter";

    // Viewname (optional)
    public $viewName = "achievementUnlocked";

    // Content
    public function html()
    {
        return Yii::t(
            'SomethingHappend.views_notifications_somethingHappened',
            "%someUser% did something cool.",
            [ '%someUser%' => '<strong>' . Html::encode($originator->displayName) . '</strong>' ]
        );
    }
}
