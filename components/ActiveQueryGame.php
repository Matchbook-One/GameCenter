<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use humhub\events\ActiveQueryEvent;
use yii\db\ActiveQuery;

use const SORT_ASC;

/**
 * ActiveQueryGame
 */
class ActiveQueryGame extends ActiveQuery
{
  /**
   * @event Event an event that is triggered when only visible users are requested via [[visible()]].
   */
  const EVENT_CHECK_VISIBILITY = 'checkVisibility';

  /**
   * @event Event an event that is triggered when only active users are requested via [[active()]].
   */
  const EVENT_CHECK_ACTIVE = 'checkActive';

  /**
   * Limit to active games
   *
   * @return ActiveQueryGame the query
   */
  public function active()
  {
    $this->trigger(self::EVENT_CHECK_ACTIVE, new ActiveQueryEvent(['query' => $this]));

    return $this->andWhere(['game.status' => Game::STATUS_ENABLED]);
  }

  /**
   * Adds default game order
   *
   * @return ActiveQueryGame the query
   */
  public function defaultOrder()
  {
    $this->addOrderBy(['title' => SORT_ASC]);

    return $this;
  }

  /**
   * @returns string[]
   */
  protected function getSearchableFields(): array
  {
    return ['game.title', 'game.description', 'game.module'];
  }
}
