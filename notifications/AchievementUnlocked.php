<?php

/**
 * @since   1.0.0
 * @author  Christian Seiler <christian@christianseiler.ch>
 */

namespace fhnw\modules\gamecenter\notifications;

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\notification\components\NotificationCategory;
use humhub\modules\user\models\User;
use yii\db\ActiveRecord;

/**
 * Notifies the player about an unlocked Achievement
 *
 * @package GameCenter/Notifications
 * @method about(ActiveRecord $source)
 * @method send(User $user)
 */
class AchievementUnlocked extends BaseNotification
{

  /**
   * @inheritdoc
   * @var string $moduleId Module Id
   */
  public $moduleId = 'gamecenter';

  /**
   * @inheritdoc
   * @var bool $requireOriginator
   */
  public $requireOriginator = false;

  /**
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function html(): string
  {
    return GameCenterModule::t(
        'notification',
        "You've unlocked an Achievement in {gameTitle}",
        ['gameTitle' => $this->getGameTitle()]
    );
  }

  /**
   * @inheritdoc
   * @noinspection PhpMissingParentCallCommonInspection
   */
  protected function category(): NotificationCategory
  {
    return new GameCenterNotificationCategory();
  }

  private function getGameTitle(): string
  {
    return '';
  }

}
