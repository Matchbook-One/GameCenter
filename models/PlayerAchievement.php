<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\helpers\DateTime;
use humhub\components\ActiveRecord;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for the table "player_achievement".
 *
 * @property int $id
 * @property int $percent_completed A percentage value that states how far the player has progressed on the achievement.
 * @property int $player_id The identifier for the Player.
 * @property string $achievement_id The identifier for the Achievement Description.
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property-read Player $player
 * @property-read Achievement $achievement
 * @property-read Game $game
 */
#[Schema(properties: [
  new Property('id', type: 'int'),
  new Property('percent_completed', type: 'int', maximum: 100, minimum: 0),
  new Property('updated_at', type: 'string', format: 'datetime'),
  new Property('player', ref: '#/components/schemas/Player'),
  new Property('achievement', ref: '#/components/schemas/Achievement')
])]
class PlayerAchievement extends ActiveRecord
{

  /**
   * @inheritdoc
   * @static
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return 'player_achievement';
  }

  /**
   * @inheritdoc
   * @return array<string,string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function attributeLabels(): array
  {
    return [
      'id'               => 'ID',
      'description'      => GameCenterModule::t('base', 'Achievement'),
      'percentCompleted' => GameCenterModule::t('base', 'Percent completed'),
      'player'           => GameCenterModule::t('base', 'Player'),
      'lastReportedDate' => GameCenterModule::t('base', 'Last Reported Date'),
      'isCompleted'      => GameCenterModule::t('base', 'Is Completed')
    ];
  }

  /**
   * This method is called at the beginning of inserting or updating a record.
   *
   * @inheridoc
   *
   * @param bool $insert whether this method called while inserting a record.
   *                     If `false`, it means the method is called while updating a record.
   *
   * @return bool whether the insertion or updating should continue.
   *              If `false`, the insertion or updating will be cancelled.
   */
  public function beforeSave($insert): bool
  {
    if (!isset($this->player_id)) {
      $this->player_id = Yii::$app->user->id;
    }

    return parent::beforeSave($insert);
  }

  /**
   * @returns ActiveQuery
   */
  public function getAchievement(): ActiveQuery
  {
    return $this->hasOne(Achievement::class, ['id' => 'achievement_id']);
  }

  public function getDescription(): string
  {
    if ($this->isCompleted() || !$this->isSecret()) {
      return $this->achievement->description;
    }

    return GameCenterModule::t('achievement', 'This Achievement is hidden');
  }

  /**
   * getGame
   *
   * @return \yii\db\ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id'])
                ->via('achievement');
  }

  /**
   * The player who earned the achievement.
   *
   * @return \yii\db\ActiveQuery
   */
  public function getPlayer(): ActiveQuery
  {
    return $this->hasOne(Player::class, ['id' => 'player_id']);
  }

  public function getTitle(): string
  {
    if ($this->isCompleted() || !$this->isSecret()) {
      return $this->achievement->title;
    }

    return GameCenterModule::t('achievement', 'Secret Achievement');
  }

  /**
   * A Boolean value that states whether the player has completed the achievement.
   *
   * @return bool
   */
  public function isCompleted(): bool
  {
    return $this->percent_completed == 100.0;
  }

  public function isSecret(): bool
  {
    return $this->achievement->secret;
  }

  /**
   * The last time your game reported progress on the achievement for the player.
   *
   * @return \DateTime
   */
  public function lastReportedDate(): \DateTime
  {
    return DateTime::date($this->updated_at);
  }

  /**
   * @inheritdoc
   * @return mixed[]
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): array
  {
    return [
      [['percent_completed'], 'integer', 'min' => 0, 'max' => 100],
      [['percent_completed'], 'default', 'value' => 0]
    ];
  }

  public function showProgress(): bool
  {
    return $this->achievement->show_progress;
  }

}
