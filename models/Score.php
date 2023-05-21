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
 * @property int $id
 * @property int $player_id
 * @property int $game_id
 * @property int $score
 * @property-read string $timestamp
 * @property-read \fhnw\modules\gamecenter\models\Game $game
 * @property-read \fhnw\modules\gamecenter\models\Player $player
 * @package GameCenter/Models
 */
class Score extends ActiveRecord
{

  /** @returns string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string { return 'score'; }

  /**
   * @return array
   */
  public function getDefinition(): array
  {
    return [
      'id'        => $this->id,
      'score'     => $this->score,
      'timestamp' => $this->timestamp
    ];
  }

  /** @return \yii\db\ActiveQuery */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  /** @return \yii\db\ActiveQuery */
  public function getPlayer(): ActiveQuery
  {
    return $this->hasOne(Player::class, ['id' => 'player_id']);
  }

}
