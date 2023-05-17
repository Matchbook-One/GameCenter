<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use humhub\events\ActiveQueryEvent;
use humhub\modules\content\components\AbstractActiveQueryContentContainer;
use humhub\modules\user\models\User;
use Throwable;
use Yii;
use yii\db\ActiveQuery;

use const SORT_ASC;

/**
 * ActiveQueryGame
 *
 * @package GameCenter/Components
 */
class ActiveQueryGame extends AbstractActiveQueryContentContainer
{

  /**
   * @var string EVENT_CHECK_VISIBILITY
   * @event Event an event that is triggered when only visible users are requested via [[visible()]].
   */
  public const EVENT_CHECK_VISIBILITY = 'checkVisibility';

  /**
   * @var string EVENT_CHECK_ACTIVE
   * @event Event an event that is triggered when only active users are requested via [[active()]].
   */
  public const EVENT_CHECK_ACTIVE = 'checkActive';

  /**
   * Limit to active games only
   *
   * @return ActiveQueryGame
   */
  public function active(): ActiveQueryGame
  {
    $this->trigger(self::EVENT_CHECK_ACTIVE, new ActiveQueryEvent(['query' => $this]));

    return $this->andWhere(['game.status' => Game::STATUS_ENABLED]);
  }

  /**
   * Adds default game order
   *
   * @return ActiveQueryGame
   */
  public function defaultOrder(): ActiveQueryGame
  {
    $this->addOrderBy(['title' => SORT_ASC]);

    return $this;
  }

  /**
   * @inheritdoc
   *
   * @param ?User $user
   *
   * @return ActiveQuery
   */
  public function visible(?User $user = null): ActiveQuery
  {
    $this->trigger(self::EVENT_CHECK_VISIBILITY, new ActiveQueryEvent(['query' => $this]));

    if ($user === null && !Yii::$app->user->isGuest) {
      try {
        $user = Yii::$app->user->getIdentity();
      }
      catch (Throwable $e) {
        Yii::error($e, 'game');
      }
    }

    if ($user !== null) {
      /*
      if ($user->can(ManageGames::class)) {
        return $this;
      }
      */
      $this->andWhere(['=', 'game.status', Game::STATUS_ENABLED]);
    }
    else {
      $this->andWhere(['=', 'game.status', Game::STATUS_ENABLED]);
    }

    return $this;
  }

  /**
   * @inheritdoc
   * @returns string[]
   */
  protected function getSearchableFields(): array
  {
    return ['game.title', 'game.description', 'game.module'];
  }

}
