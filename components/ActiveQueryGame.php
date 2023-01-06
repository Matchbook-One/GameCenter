<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use humhub\events\ActiveQueryEvent;
use humhub\modules\user\models\User;
use Throwable;
use Yii;
use yii\db\ActiveQuery;

/**
 *
 */
class ActiveQueryGame extends ActiveQuery {
  public const MAX_SEARCH_NEEDLES = 5;

  /**
   * @event Event an event that is triggered when only visible games are requested via [[visible()]].
   */
  public const EVENT_CHECK_VISIBILITY = 'checkVisibility';

  /**
   * Performs a game full text search
   *
   * @param array|string $keywords
   * @param array        $columns
   *
   * @return ActiveQueryGame the query
   */
  public function search(array|string $keywords, array $columns = [
    'game.name',
    'game.title',
    'game.description'
  ]): ActiveQueryGame {
    if (empty($keywords)) {
      return $this;
    }

    $this->joinWith('contentContainerRecord');

    if (!is_array($keywords)) {
      $keywords = explode(' ', $keywords);
    }

    foreach (array_slice($keywords, 0, static::MAX_SEARCH_NEEDLES) as $keyword) {
      $conditions = [];
      foreach ($columns as $field) {
        $conditions[] = ['LIKE', $field, $keyword];
      }
      $this->andWhere(array_merge(['OR'], $conditions));
    }

    return $this;
  }


  /**
   * Only returns games which are visible for this user
   *
   * @param User|null $user
   *
   * @return ActiveQueryGame the query
   */
  public function visible(User $user = null): ActiveQueryGame {
    $this->trigger(self::EVENT_CHECK_VISIBILITY, new ActiveQueryEvent(['query' => $this]));

    if ($user === null && !Yii::$app->user->isGuest) {
      try {
        $user = Yii::$app->user->getIdentity();
      } catch (Throwable $e) {
        Yii::error($e, 'game');
      }
    }

    if ($user !== null) {
      $this->andWhere([
                        'OR',
                        ['IN', 'game.visibility', [Game::VISIBILITY_ALL, Game::VISIBILITY_REGISTERED_ONLY]],
                        /*[
                          'AND',
                          ['=', 'game.visibility', Game::VISIBILITY_NONE],
                          ['IN', 'game.id', Membership::find()->select('game')->where(['user_id' => $user->id])]
                        ]*/
                      ]);
    } /*else {
       $this->andWhere(['!=', 'game.visibility', Game::VISIBILITY_NONE]);
    }*/

    return $this;
  }
}