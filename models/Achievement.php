<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\components\GameModule;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\components\ActiveRecord;
use humhub\components\behaviors\GUID;
use humhub\modules\search\jobs\DeleteDocument;
use Yii;
use yii\db\ActiveQuery;

use function get_class;

/**
 * This is the model class for the table "player_achievement".
 *
 * @property int $id
 * @property string $guid
 * @property string $name
 * @property string $title
 * @property string $description
 * @property ?string $image
 * @property int $game_id
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property-read PlayerAchievement[] $achievements
 * @property-read Game $game
 * @mixin    GUID
 * @phpstan-import-type AchievementConfig from GameModule
 */
class Achievement extends ActiveRecord
{

  public const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';

  public const STATUS_ACTIVE = 0;

  public const STATUS_SOFT_DELETED = 1;

  /**
   * @inheritdoc
   * @static
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string { return 'achievement'; }

  /**
   * @inheritdoc
   * @return array<string, string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function attributeLabels(): array
  {
    return [
      'id'          => 'ID',
      'guid'        => 'GUID',
      'name'        => GameCenterModule::t('base', 'Name'),
      'title'       => GameCenterModule::t('base', 'Title'),
      'description' => GameCenterModule::t('base', 'Description'),
      'game_id'     => GameCenterModule::t('base', 'Game'),
      'created_at'  => GameCenterModule::t('base', 'Created at'),
      'created_by'  => GameCenterModule::t('base', 'Created by'),
      'updated_at'  => GameCenterModule::t('base', 'Updated at'),
      'updated_by'  => GameCenterModule::t('base', 'Updated by')
    ];
  }

  /**
   * Before Delete of a Achievement
   *
   * @return bool
   */
  public function beforeDelete(): bool
  {
    $this->softDelete();

    return parent::beforeDelete();
  }

  /**
   * @inheritdoc
   * @return string[]
   * @phpstan-return array<class-string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function behaviors(): array
  {
    return [
      GUID::class
    ];
  }

  /**
   * @return ActiveQuery
   */
  public function getAchievements(): ActiveQuery
  {
    return $this->hasMany(PlayerAchievement::class, ['description_id' => 'id']);
  }

  /**
   * getGame
   *
   * @return ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  /**
   * @inheritdoc
   * @return mixed[]
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): array
  {
    return [];
  }

  /**
   * @return bool
   */
  public function softDelete(): bool
  {
    $this->trigger(self::EVENT_BEFORE_SOFT_DELETE);

    $config = [
      'activeRecordClass' => get_class($this),
      'primaryKey'        => $this->id
    ];
    Yii::$app->queue->push(new DeleteDocument($config));

    $this->updateAttributes(['status' => self::STATUS_SOFT_DELETED]);

    return true;
  }

}
