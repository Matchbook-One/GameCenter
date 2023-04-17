<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\components\ActiveQueryGame;
use fhnw\modules\gamecenter\events\GameEvent;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\components\ActiveRecord;
use humhub\components\behaviors\GUID;
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\search\jobs\DeleteDocument;
use humhub\modules\space\widgets\Wall;
use Throwable;
use Yii;
use Yii\db\ActiveQuery;

use const SORT_ASC;

/**
 * This is the model class for table "game".
 *
 * @property int                           $id
 * @property string                        $guid
 * @property string                        $module
 * @property string                        $title
 * @property string                        $description
 * @property string[]                      tags
 * @property int                           $status
 * @property string                        $created_at
 * @property int                           $created_by
 * @property string                        $updated_at
 * @property int                           $updated_by
 * @property-read Score[]                  $scores
 * @property-read AchievementDescription[] $achievementDescriptions
 * @mixin    GUID
 */
class Game extends ActiveRecord implements Searchable
{

  public const STATUS_DISABLED = 0;

  public const STATUS_ENABLED = 1;

  public const STATUS_ARCHIVED = 2;

  public const STATUS_SOFT_DELETED = 3;

  /**
   * @event An event that is triggered when the game is soft deleted and also before complete deletion.
   */
  public const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';

  /**
   * @inheritdoc
   * @return       ActiveQueryGame the newly created [[ActiveQueryGame]] instance.
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function find(): ActiveQueryGame
  {
    return new ActiveQueryGame(static::class);
  }

  /**
   * @inheritdoc
   * @return       string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return 'gc_game';
  }

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
      'guid'        => 'GUID',
      'module'      => GameCenterModule::t('base', 'Module'),
      'title'       => GameCenterModule::t('base', 'Title'),
      'description' => GameCenterModule::t('base', 'Description'),
      'tags'        => GameCenterModule::t('base', 'Tags'),
      'status'      => GameCenterModule::t('base', 'Status'),
      'created_at'  => GameCenterModule::t('base', 'Created at'),
      'created_by'  => GameCenterModule::t('base', 'Created by'),
      'updated_at'  => GameCenterModule::t('base', 'Updated at'),
      'updated_by'  => GameCenterModule::t('base', 'Updated by'),
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
   * getGameImage
   *
   * @TODO
   * @return string
   */
  public function getGameImage(): string
  {
    return '';
  }

  /**
   * Returns an array of information used by search subsystem.
   * Function is defined in interface [[Searchable]].
   *
   * @return array<string,string>
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

  /**
   * getWallOut
   *
   * @return string
   * @throws Throwable
   */
  public function getWallOut(): string
  {
    return Wall::widget();
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
    AchievementDescription::deleteAll(['game_id' => 'id']);

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

  /**
   * @return \Yii\db\ActiveQuery
   */
  public function getScores(): ActiveQuery
  {
    return $this->hasMany(Score::class, ['game_id' => 'id']);
  }

  /**
   * @return \Yii\db\ActiveQuery
   */
  public function getAchievementDescriptions(): ActiveQuery
  {
    return $this->hasMany(AchievementDescriptions::class, ['game_id' => 'id']);
  }

  /**
   * @param \fhnw\modules\gamecenter\models\User $user
   *
   * @return Score
   */
  public function getHighscore(User $user = null): Score
  {
    if ($user == null) {
      $user = Yii::$app->user;
    }
    /** @var Score $score */
    $score = $this->getScores()->where(['player_id' => $user->id])->orderBy(['score' => SORT_ASC])->one();

    return $score;
  }

  /**
   * @param int $score
   *
   * @return bool
   * @static
   */
  public function saveScore(int $score): bool
  {
    $newScore = new Score();
    $newScore->score = $score;
    $newScore->game_id = $this->id;
    $newScore->player_id = Yii::$app->user->id;

    return $newScore->save();
  }
}
