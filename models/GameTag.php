<?php

namespace fhnw\modules\gamecenter\models;

use humhub\components\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * @property int $game_id
 * @property string $tag
 * @property-read \fhnw\modules\gamecenter\models\Game $game
 */
class GameTag extends ActiveRecord
{

  /** @noinspection PhpMissingParentCallCommonInspection */
  public static function tableName(): string
  {
    return 'game_tag';
  }

  /**
   * @return ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  /** @return array
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): array
  {
    return [
      [['game_id', 'tag'], 'required'],
      [['game_id'], 'integer'],
      [['tag'], 'safe']
    ];
  }

}