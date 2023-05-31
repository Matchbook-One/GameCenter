<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\notifications;

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\modules\notification\components\NotificationCategory;

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
   * @return string
   */
  public function getDescription(): string
  {
    return GameCenterModule::t('notification', 'Receive Notifications of GameCenter events.');
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
