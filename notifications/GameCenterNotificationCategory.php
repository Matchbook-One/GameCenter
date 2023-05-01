<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\notifications;

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\modules\notification\components\NotificationCategory;
use humhub\modules\notification\targets\{BaseTarget, MailTarget, MobileTarget, WebTarget};
use Yii;

/**
 * GameCenterNotificationCategory
 *
 * @package GameCenter/Notifications
 */
class GameCenterNotificationCategory extends NotificationCategory
{
  /**
   * @inheritdoc
   * @var string $id
   */
  public $id = 'game';

  /**
   * @inheritdoc
   *
   * @param BaseTarget $target
   *
   * @return bool
   */
  public function getDefaultSetting(BaseTarget $target): bool
  {
    switch ($target->id) {
      case MailTarget::getId():
      case WebTarget::getId():
      case MobileTarget::getId():
        return true;
      default:
        return $target->defaultSetting;
    }
  }

  /**
   * @inheritdoc
   * @return string
   */
  public function getDescription(): string
  {
    return GameCenterModule::t('notification', 'Receive Notifications of GameCenter events.');
  }

  /**
   * @inheritdoc
   * @returns array<string>
   * @throws \yii\base\InvalidConfigException
   */
  public function getFixedSettings(): array
  {
    /** @var WebTarget $webTarget */
    $webTarget = Yii::createObject(WebTarget::class);

    return [$webTarget->id];
  }

  /**
   * @inheritdoc
   * @return string
   */
  public function getTitle(): string
  {
    return GameCenterModule::t('base', 'GameCenter');
  }
}
