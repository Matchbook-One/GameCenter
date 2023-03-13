<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use humhub\events\ActiveQueryEvent;
use humhub\modules\user\models\User;
use Throwable;
use Yii;
use yii\db\ActiveQuery;

use function array_slice;
use function is_array;

/**
 * ActiveQueryGame
 */
class ActiveQueryGame extends ActiveQuery
{
    /**
     * @var int
     */
    public const MAX_SEARCH_NEEDLES = 5;

    /**
     * @event Event an event that is triggered when only visible games are requested via [[visible()]].
     * @see   ActiveQueryGame::visible()
     * @var   string
     */
    public const EVENT_CHECK_VISIBILITY = 'checkVisibility';

    /**
     * Performs a game full text search
     *
     * @param string[]|string $keywords the search keywords
     * @param string[]        $columns  the columns to be searched
     *
     * @return ActiveQueryGame the query
     */
    public function search($keywords, array $columns=[
        'game.name',
        'game.title',
        'game.description',
    ]
    ): ActiveQueryGame {
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
                $conditions[] = [
                    'LIKE',
                    $field,
                    $keyword
                ];
            }

            $this->andWhere(array_merge(['OR'], $conditions));
        }

        return $this;
    }

    /**
     * Only returns games which are visible for this user
     *
     * @TODO
     *
     * @param User $user the user
     *
     * @return ActiveQueryGame the query
     */
    public function visible(?User $user=null): ActiveQueryGame
    {
        $this->trigger(self::EVENT_CHECK_VISIBILITY, new ActiveQueryEvent(['query' => $this]));

        if ($user === null && !Yii::$app->user->isGuest) {
            try {
                $user = Yii::$app->user->getIdentity();
            } catch (Throwable $error) {
                Yii::error($error, 'game');
            }
        }

        $user->getId();

        return $this;
    }
}
