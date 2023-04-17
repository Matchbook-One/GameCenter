<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use humhub\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Score".
 *
 * @property int         $id
 * @property int         $player_id
 * @property int         $game_id
 * @property int         $score
 * @property-read string $timestamp
 * @property-read Game   $game
 * @property-read User   $player
 */
class Score extends ActiveRecord
{

  /** @returns string */
  public static function tableName(): string
  {
    return 'gc_score';
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getPlayer(): ActiveQuery
  {
    return $this->hasOne(User::class, ['id' => 'player_id']);
  }
}
