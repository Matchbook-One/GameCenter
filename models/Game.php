<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\models;

use humhub\components\behaviors\GUID;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerSettingsManager;
use humhub\modules\gamecenter\components\ActiveQueryGame;
use humhub\modules\gamecenter\events\GameEvent;
use humhub\modules\search\events\SearchAddEvent;
use humhub\modules\search\interfaces\Searchable;
use humhub\modules\search\jobs\DeleteDocument;
use Yii;

/**
 * This is the model class for table "game".
 *
 * @property int           $id
 * @property string        $guid
 * @property string        $name
 * @property string        $title
 * @property string        $description
 * @property Achievement[] $achievements
 * @property int           $status
 * @property int           $visibility
 * @property string        $created_at
 * @property int           $created_by
 * @property string        $updated_at
 * @property int           $updated_by
 */
class Game extends ContentContainerActiveRecord implements Searchable {

  /** Game Status Flags */
  public const STATUS_DISABLED = 0;
  public const STATUS_ENABLED = 1;
  public const STATUS_NEED_APPROVAL = 2;
  public const STATUS_SOFT_DELETED = 3;

  /**
   * Visibility Modes
   */
  public const VISIBILITY_REGISTERED_ONLY = 1; // Only for registered members
  public const VISIBILITY_ALL = 2; // Visible for all (also guests)
  public const VISIBILITY_HIDDEN = 3; // Invisible

  /**
   * @event Event an event that is triggered when the user visibility is checked via [[isVisible()]].
   */
  public const EVENT_CHECK_VISIBILITY = 'checkVisibility';


  /**
   * @event GameEvent an event that is triggered when the game is soft deleted (without contents) and also before complete deletion.
   */
  public const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';


  /**
   * @inheritdoc
   */
  public static function tableName(): string {
    return 'game';
  }

  /**
   * {@inheritdoc}
   * @return ActiveQueryGame the newly created [[ActiveQuery]] instance.
   */
  public static function find(): ActiveQueryGame {
    return new ActiveQueryGame(get_called_class());
  }

  /**
   * @inheritdoc
   */
  public function rules(): array {
    return [
      [['visibility', 'status'], 'integer'],
      [['name'], 'required'],
      [['description'], 'string'],
      [['description'], 'string', 'max' => 100],
      [['visibility'], 'in', 'range' => [0, 1, 2]],
      [['visibility'], 'checkVisibility'],
      [['guid', 'name'], 'string', 'max' => 45, 'min' => 2],
    ];

  }

  /**
   * @inheritdoc
   */
  public function attributeLabels(): array {
    return [
      'id'           => 'ID',
      'guid'         => 'Guid',
      'name'         => Yii::t('GamecenterModule.base', 'Name'),
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
   * Returns an array of informations used by search subsystem.
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
   * @inheritdoc
   */
  public function behaviors(): array {
    return [
      GUID::class
    ];
  }

  /**
   * Specifies whether the Game should appear in game lists or in the search.
   *
   * @return bool is visible
   */
  public function isVisible(): bool {
    $event = new GameEvent(['game' => $this, 'result' => ['isVisible' => true]]);
    $this->trigger(self::EVENT_CHECK_VISIBILITY, $event);

    return $event->result['isVisible'] && $this->isActive() && $this->visibility !== Game::VISIBILITY_HIDDEN;
  }

  /**
   * @return bool true if the game status is enabled else false
   */
  public function isActive(): bool {
    return $this->status === self::STATUS_ENABLED;
  }

  /**
   * Before Delete of a Game
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

    Yii::$app->queue->push(new DeleteDocument([
                                                'activeRecordClass' => get_class($this),
                                                'primaryKey'        => $this->id
                                              ]));

    // Cleanup related tables
    /*
    Invite::deleteAll(['user_originator_id' => $this->id]);
    Invite::deleteAll(['email' => $this->email]);
    Follow::deleteAll(['user_id' => $this->id]);
    Follow::deleteAll(['object_model' => static::class, 'object_id' => $this->id]);
    Password::deleteAll(['user_id' => $this->id]);
    GroupUser::deleteAll(['user_id' => $this->id]);
    Session::deleteAll(['user_id' => $this->id]);
    Friendship::deleteAll(['user_id' => $this->id]);
    Friendship::deleteAll(['friend_user_id' => $this->id]);
    Auth::deleteAll(['user_id' => $this->id]);
*/
    $this->updateAttributes(['status' => self::STATUS_SOFT_DELETED]);

    return true;
  }

  /**
   * Before Save Addons
   *
   * @param bool $insert
   *
   * @return bool
   */
  public function beforeSave($insert): bool {
    if ($insert) {
      if ($this->status == '') {
        $this->status = Game::STATUS_ENABLED;
      }
    }
    return parent::beforeSave($insert);
  }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  public function scenarios() {
    $scenarios = parent::scenarios();
//    $scenarios[static::SCENARIO_EDIT] = ['name', 'color', 'description', 'about', 'tagsField', 'blockedUsersField', 'join_policy', 'visibility', 'default_content_visibility'];
//    $scenarios[static::SCENARIO_CREATE] = ['name', 'color', 'description', 'join_policy', 'visibility'];
//    $scenarios[static::SCENARIO_SECURITY_SETTINGS] = ['default_content_visibility', 'join_policy', 'visibility'];

    return $scenarios;
  }

  /**
   * @inheritdoc
   */
  public function getDisplayName(): string {
    return $this->title;
  }

  /**
   * @inheritdoc
   */
  public function getDisplayNameSub(): string {
    return $this->description;
  }

  /**
   * @return ContentContainerSettingsManager
   */
  public function getSettings(): ContentContainerSettingsManager {
    $module = Yii::$app->getModule('gamecenter');
    return $module->settings->contentContainer($this);
  }
}