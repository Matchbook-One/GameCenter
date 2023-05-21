<?php

/**
 * @package GameCenter/Models
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\components\Period;
use fhnw\modules\gamecenter\GameCenterModule;
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

  /**
   * @return \fhnw\modules\gamecenter\components\Period|null
   */
  public function getCurrentPeriod(): ?Period
  {
    return match ($this->type) {
      Leaderboard::RECURRING_DAILY   => Period::daily(),
      Leaderboard::RECURRING_WEEKLY  => Period::weekly(),
      Leaderboard::RECURRING_MONTHLY => Period::month(),
      default                        => null,
    };
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  public function getTitle(): string
  {
    return match ($this->type) {
      Leaderboard::RECURRING_DAILY   => GameCenterModule::t('leaderboard', 'Daily Leaderboard'),
      Leaderboard::RECURRING_WEEKLY  => GameCenterModule::t('leaderboard', 'Weekly Leaderboard'),
      Leaderboard::RECURRING_MONTHLY => GameCenterModule::t('leaderboard', 'Monthly Leaderboard'),
      Leaderboard::CLASSIC           => GameCenterModule::t('leaderboard', 'All time Leaderboard'),
    };
  }

}

