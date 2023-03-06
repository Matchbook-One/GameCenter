<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\components\ActiveQueryGame;
use fhnw\modules\gamecenter\events\GameEvent;
use fhnw\modules\gamecenter\Module;
use humhub\components\ActiveRecord;
use humhub\components\behaviors\GUID;
use humhub\modules\content\components\ContentContainerSettingsManager;
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\search\jobs\DeleteDocument;
use humhub\modules\space\widgets\Wall;
use Throwable;
use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int    $id
 * @property string $guid
 * @property string $module
 * @property string $title
 * @property string $description
 * @property int    $genre_id
 * @property int    $status
 * @property string $created_at
 * @property int    $created_by
 * @property string $updated_at
 * @property int    $updated_by
 * @property int    $contentcontainer_id
 *
 * @mixin GUID
 */
class Game extends ActiveRecord implements Searchable {

  /* Game Status Flags */
  public const STATUS_DISABLED = 0;
  public const STATUS_ENABLED = 1;
  public const STATUS_ARCHIVED = 2;
  public const STATUS_SOFT_DELETED = 3;

  /** An event that is triggered when the game is soft deleted and also before complete deletion.
   *
   * @event
   */
  public const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';

  /**
   * @inheritdoc
   *
   * @return ActiveQueryGame the newly created [[ActiveQuery]] instance.
   *
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function find(): ActiveQueryGame {
    return new ActiveQueryGame(static::class);
  }

  /**
   * @inheritdoc
   *
   * @return string
   *
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string {
    return 'game';
  }

  /**
   * @return void
   *
   * @TODO Implementation
   */
  public function addAchievement(): void {}

  // /**
  //  * @param string $title
  //  * @param string $description
  //  *
  //  * @return void
  //  */
  // public function addGenre(string $title, string $description = ''): void {
  //   $this->genre_id = Genre::make($title, $description)->id;
  // }

  /**
   * Archive this Game
   *
   * @return void
   */
  public function archive(): void {
    $this->status = self::STATUS_ARCHIVED;
    $this->save();
  }

  /**
   * @inheritdoc
   *
   * @return array
   *
   * @PhpMissingParentCallCommonInspection
   */
  public function attributeLabels(): array {
    return [
      'id'           => 'ID',
      'guid'         => 'Guid',
      'module'       => Yii::t('GamecenterModule.base', 'Module'),
      'title'        => Yii::t('GamecenterModule.base', 'Title'),
      'description'  => Yii::t('GamecenterModule.base', 'Description'),
      'genre'        => Yii::t('GamecenterModule.base', 'Genre'),
      'achievements' => Yii::t('GamecenterModule.base', 'Achievements'),
      'status'       => Yii::t('GamecenterModule.base', 'Status'),
      'visibility'   => Yii::t('GamecenterModule.base', 'Visibility'),
      'created_at'   => Yii::t('GamecenterModule.base', 'Created at'),
      'created_by'   => Yii::t('GamecenterModule.base', 'Created by'),
      'updated_at'   => Yii::t('GamecenterModule.base', 'Updated at'),
      'updated_by'   => Yii::t('GamecenterModule.base', 'Updated by')
    ];
  }

  /**
   * Before Delete of a Game
   *
   * @return bool
   */
  public function beforeDelete(): bool {
    $this->softDelete();

    return parent::beforeDelete();
  }

  /**
   * @return bool
   */
  public function softDelete(): bool {
    $this->trigger(self::EVENT_BEFORE_SOFT_DELETE, new GameEvent(['user' => $this]));

    $config = [
      'activeRecordClass' => get_class($this),
      'primaryKey'        => $this->id
    ];
    Yii::$app->queue->push(new DeleteDocument($config));

    // Cleanup related tables
    Achievement::deleteAll(['game_id' => 'id']);

    $this->updateAttributes(['status' => self::STATUS_SOFT_DELETED]);

    return true;
  }

  /**
   * @inheritdoc
   *
   * @param bool $insert
   *
   * @return bool
   */
  public function beforeSave($insert): bool {
    if (empty($this->status)) {
      $this->status = self::STATUS_ENABLED;
    }

    return parent::beforeSave($insert);
  }

  /**
   * @inheritdoc
   *
   * @return array
   */
  public function behaviors(): array {
    return [
      GUID::class
    ];
  }

  /**
   * @return Achievement[] List of Achievements
   */
  public function getAchievements(): array {
    return $this->hasMany(Achievement::class, ['game_id' => 'id']);
  }

  /**
   * getGameImage
   *
   * @return string
   */
  public function getGameImage(): string {
    return '';
  }

  /**
   * getGenre
   *
   * @return Genre
   */
  public function getGenre(): Genre {
    return $this->hasOne(Genre::class, ['id' => 'genre_id']);
  }

  /**
   * getId
   *
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * Returns an array of information used by search subsystem.
   * Function is defined in interface ISearchable
   *
   * @return array
   */
  public function getSearchAttributes(): array {
    $attributes = [
      'title'       => $this->title,
      'description' => $this->description
    ];

    $this->trigger(self::EVENT_SEARCH_ADD, new SearchAddEvent($attributes));

    return $attributes;
  }

  /**
   * getSettings
   *
   * @return ContentContainerSettingsManager
   */
  public function getSettings(): ContentContainerSettingsManager {
    /** @var Module $module */
    $module = Yii::$app->getModule('gamecenter');

    return $module->settings->contentContainer($this);
  }

  /**
   * getUrl
   *
   * @return string
   */
  public function getUrl(): string {
    return '';
  }

  /**
   * getWallOut
   *
   * @return string
   * @throws Throwable
   */
  public function getWallOut(): string {
    return Wall::widget(['game' => $this]);
  }

  /**
   * Returns whether a Game is archived.
   *
   * @return bool
   */
  public function isArchived(): bool {
    return $this->status === self::STATUS_ARCHIVED;
  }

  /**
   * Specifies whether the Game should appear in game lists or in the search.
   *
   * @return bool is visible
   */
  public function isVisible(): bool {
    $event = new GameEvent(['game' => $this, 'result' => ['isVisible' => true]]);

    // $this->trigger(self::EVENT_CHECK_VISIBILITY, $event);

    return $event->result['isVisible'] && $this->isActive();
  }

  /**
   * @return bool true if the game status is enabled else false
   */
  public function isActive(): bool {
    return $this->status === self::STATUS_ENABLED;
  }

  /**
   * @inheritdoc
   *
   * @return array
   *
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): array {
    return [
      [['status'], 'integer'],
      [['module', 'title'], 'required'],
      [['description'], 'string'],
      [['description'], 'string', 'max' => 100],
      [['status'], 'in', 'range' => [0, 1]],
      [['guid', 'title'], 'string', 'max' => 45, 'min' => 2],
    ];
  }

  /**
   * scenarios
   *
   * @inerhitdoc
   *
   * @return array
   */
  public function scenarios(): array {
    $scenarios = parent::scenarios();
    //    $scenarios[static::SCENARIO_EDIT] = ['name', 'color', 'description', 'about', 'tagsField', 'blockedUsersField', 'join_policy', 'visibility', 'default_content_visibility'];
    //    $scenarios[static::SCENARIO_CREATE] = ['name', 'color', 'description', 'join_policy', 'visibility'];
    //    $scenarios[static::SCENARIO_SECURITY_SETTINGS] = ['default_content_visibility', 'join_policy', 'visibility'];

    return $scenarios;
  }

  /**
   * Unarchive this Game
   *
   * @return void
   */
  public function unarchive(): void {
    $this->status = self::STATUS_ENABLED;
    $this->save();
  }
}
