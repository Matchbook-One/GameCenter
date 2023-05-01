<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\notifications;

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\notification\components\NotificationCategory;
use yii\bootstrap\Html;

/**
 * AchievementUnlockedNotification
 *
 * @package GameCenter/Notifications
 * @property \fhnw\modules\gamecenter\models\Game $source
 */
class AchievementUnlocked extends BaseNotification
{

  /**
   * @inheritdoc
   * @var string $moduleId
   */
  public $moduleId = 'gamecenter';

  /**
   * @inheritdoc
   * @var string $viewName
   */
  public $viewName = 'achievementUnlocked';

  /**
   * @inheritdoc
   * @return string
   */
  public function getMailSubject(): string
  {
    return $this->getInfoText($this->originator->displayName, $this->source->title);
  }

  /**
   * @inheritdoc
   * @return string
   */
  public function html(): string
  {
    return $this->getInfoText(
      Html::tag('strong', Html::encode($this->originator->displayName)),
      Html::tag('strong', Html::encode($this->source->title))
    );
  }

  /**
   * @inheritdoc
   * @return \humhub\modules\notification\components\NotificationCategory
   */
  protected function category(): NotificationCategory
  {
    return new GameCenterNotificationCategory();
  }

  /**
   * @param string $displayName
   * @param string $gameName
   *
   * @return string
   */
  private function getInfoText(string $displayName, string $gameName): string
  {
    return GameCenterModule::t('notification', '{displayName} {gameName}', [
      '{displayName}' => $displayName,
      '{gameName}'    => $gameName
    ]);
  }

}
