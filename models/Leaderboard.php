<?php

/**
 * @package GameCenter/Models
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use humhub\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for the table “leaderboard”.
 *
 * @property      int $id
 * @property      int $type The type of leaderboard, classic or recurring.
 * @property      string $game_id
 * @property-read Game $game
 */
class Leaderboard extends ActiveRecord
{

  public const CLASSIC = 0;
  public const RECURRING_DAILY = 1;
  public const RECURRING_WEEKLY = 2;
  public const RECURRING_MONTHLY = 3;

  /**
   * @inheritdoc
   * @static
   * @return       string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return 'leaderboard';
  }

  public function getCurrentPeriod()
  {
    date('w');

    switch ($this->id) {
      case Leaderboard::CLASSIC:
      case Leaderboard::RECURRING_DAILY:
      case Leaderboard::RECURRING_WEEKLY:
      case Leaderboard::RECURRING_MONTHLY:
    }
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

}

