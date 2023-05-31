<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use humhub\components\ActiveRecord;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Score".
 *
 * @package GameCenter/Models
 * @property int         $id
 * @property int         $player_id
 * @property int         $game_id
 * @property int         $score
 * @property-read string $timestamp
 * @property-read Game   $game
 * @property-read Player $player
 * @package GameCenter/Models
 */
#[Schema(properties: [
    new Property('id', type: 'integer'),
    new Property('player', type: 'integer'),
    new Property('game', type: 'integer'),
    new Property('score', type: 'integer'),
    new Property('timestamp', type: 'string', format: 'date-time')
])]
class Score extends ActiveRecord
{

  public const TABLE = 'score';

  /** @returns string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return self::TABLE;
  }

  /** @return ActiveQuery */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  /** @return ActiveQuery */
  public function getPlayer(): ActiveQuery
  {
    return $this->hasOne(Player::class, ['id' => 'player_id']);
  }

}
