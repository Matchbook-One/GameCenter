<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\components\ActiveQueryGame;
use fhnw\modules\gamecenter\components\GameModule;
use fhnw\modules\gamecenter\events\GameEvent;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\components\behaviors\GUID;
use humhub\modules\content\components\{ContentContainerActiveRecord, ContentContainerPermissionManager, ContentContainerSettingsManager};
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\search\jobs\DeleteDocument;
use humhub\modules\space\widgets\Wall;
use humhub\modules\user\models\User;
use Yii;
use Yii\db\ActiveQuery;

use const SORT_ASC;

/**
 * This is the model class for the table “game”.
 *
 * @property int $id
 * @property string $guid
 * @property string $module
 * @property string $title
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property int $created_by
 * @property User $createdBy
 * @property User $updatedBy
 * @property-read GameTag[] $gameTags
 * @property-read Score[] $scores
 * @property-read Achievement[] $achievements
 * @property int $contentcontainer_id
 * @property ContentContainerPermissionManager $permissionManager
 * @property ContentContainerSettingsManager $settings
 * @method ActiveQuery hasMany($class, array $link) See [[BaseActiveRecord::hasMany()]] for more info.
 * @method ActiveQuery hasOne($class, array $link) See [[BaseActiveRecord::hasOne()]] for more info.
 * @mixin    GUID
 * @package GameCenter/Models
 */
class Game extends ContentContainerActiveRecord implements Searchable
{

  /** @var int STATUS_DISABLED */
  public const STATUS_DISABLED = 0;

  /** @var int STATUS_ENABLED */
  public const STATUS_ENABLED = 1;

  /** @var int  STATUS_ARCHIVED */
  public const STATUS_ARCHIVED = 2;

  /** @var int STATUS_SOFT_DELETED */
  public const STATUS_SOFT_DELETED = 3;

  /**
   * @event An event that is triggered when the game is soft deleted and also before complete deletion.
   * @var string EVENT_BEFORE_SOFT_DELETE
   */
  public const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';

  /**
   * @return ActiveQueryGame
   * @static
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function find(): ActiveQueryGame { return new ActiveQueryGame(get_called_class()); }

  /**
   * @inheritdoc
   * @return       string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string { return 'game'; }

  /**
   * Archive this Game
   *
   * @return void
   */
  public function archive(): void
  {
    $this->status = self::STATUS_ARCHIVED;
    $this->save();
  }

  /**
   * @inheritdoc
   * @return array<string, string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function attributeLabels(): array
  {
    return [
      'id'          => 'ID',
      'module'      => GameCenterModule::t('base', 'Module'),
      'title'       => GameCenterModule::t('base', 'Title'),
      'description' => GameCenterModule::t('base', 'Description'),
      'tags'        => GameCenterModule::t('base', 'Tags'),
      'status'      => GameCenterModule::t('base', 'Status'),
      'created_at'  => GameCenterModule::t('base', 'Created at'),
      'created_by'  => GameCenterModule::t('base', 'Created by'),
      'updated_at'  => GameCenterModule::t('base', 'Updated at'),
      'updated_by'  => GameCenterModule::t('base', 'Updated by')
    ];
  }

  /**
   * Before Delete of a Game
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
   *
   * @param bool $insert True to insert
   *
   * @return bool
   */
  public function beforeSave($insert): bool
  {
    if (empty($this->status)) {
      $this->status = self::STATUS_ENABLED;
    }

    return parent::beforeSave($insert);
  }

  /**
   * @inheritdoc
   * @return array<class-string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function behaviors(): array
  {
    return [GUID::class];
  }

  /**
   * @return \Yii\db\ActiveQuery
   */
  public function getAchievement(): ActiveQuery
  {
    return $this->hasMany(Achievement::class, ['game_id' => 'id']);
  }

  /** @return string */
  public function getDisplayName(): string
  {
    return $this->title;
  }

  /** @return string */
  public function getDisplayNameSub(): string
  {
    return $this->description;
  }

  /**
   * getGameImage
   *
   * @TODO
   * @return string
   */
  public function getGameImage(): string
  {
    return '';
  }

  public function getGameTags(): ActiveQuery
  {
    return $this->hasMany(GameTag::class, ['game_id' => 'id']);
  }

  /**
   * @param ?\fhnw\modules\gamecenter\models\Player $player
   *
   * @return ?Score
   */
  public function getHighScore(Player $player = null): ?Score
  {
    if ($player == null) {
      $player = Yii::$app->user;
    }
    /** @var Score|null $score */
    $score = $this->getScores()
                  ->andWhere(['player_id' => $player->id])
                  ->orderBy(['score' => SORT_ASC])
                  ->one();

    return $score;
  }

  /**
   * @return \fhnw\modules\gamecenter\components\GameModule
   */
  public function getModule(): GameModule
  {
    /** @var \fhnw\modules\gamecenter\components\GameModule $module */
    $module = Yii::$app->getModule($this->module);

    return $module;
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getPlayers(): ActiveQuery
  {
    return $this->hasMany(Play::class, ['game_id' => 'id']);
  }

  /**
   * @return \Yii\db\ActiveQuery
   */
  public function getScores(): ActiveQuery
  {
    return $this->hasMany(Score::class, ['game_id' => 'id']);
  }

  /**
   * Returns an array of information used by search subsystem.
   * Function is defined in interface [[Searchable]].
   *
   * @return array<string,string> array of information used by search subsystem
   * @see    Searchable
   */
  public function getSearchAttributes(): array
  {
    $attributes = [
      'title'       => $this->title,
      'description' => $this->description,
    ];

    $this->trigger(self::EVENT_SEARCH_ADD, new SearchAddEvent($attributes));

    return $attributes;
  }

  public function getSettings(): ContentContainerSettingsManager
  {
    return GameCenterModule::$settings->contentContainer($this);
  }

  /**
   * @noinspection PhpMissingParentCallCommonInspection
   * @throws \Throwable
   */
  public function getWallOut(): string
  {
    return Wall::widget(['game' => $this]);
  }

  /**
   * @return bool true if the game status is enabled else false
   */
  public function isActive(): bool
  {
    return $this->status === self::STATUS_ENABLED;
  }

  /**
   * Returns whether a Game is archived.
   *
   * @return bool
   */
  public function isArchived(): bool
  {
    return $this->status === self::STATUS_ARCHIVED;
  }

  /**
   * @inheritdoc
   * @return       array
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): array
  {
    return [
      [['module', 'title', 'description'], 'required'],
      [['description'], 'string', 'max' => 255],
      [['status'], 'in', 'range' => [0, 1]],
      [['guid', 'title'], 'string', 'max' => 45, 'min' => 2],
    ];
  }

  /**
   * @return bool
   */
  public function softDelete(): bool
  {
    $this->trigger(self::EVENT_BEFORE_SOFT_DELETE, new GameEvent(['user' => $this]));

    $config = [
      'activeRecordClass' => get_class($this),
      'primaryKey'        => $this->id,
    ];
    Yii::$app->queue->push(new DeleteDocument($config));

    // Cleanup related tables
    Achievement::deleteAll(['game_id' => 'id']);

    $this->updateAttributes(['status' => self::STATUS_SOFT_DELETED]);

    return true;
  }

  /**
   * Unarchive this Game
   *
   * @return void
   */
  public function unarchive(): void
  {
    $this->status = self::STATUS_ENABLED;
    $this->save();
  }

}
