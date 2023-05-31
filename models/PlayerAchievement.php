<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\activities\AchievementUnlock;
use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\helpers\DateTime;
use fhnw\modules\gamecenter\helpers\Url;
use fhnw\modules\gamecenter\notifications\AchievementUnlocked;
use humhub\components\ActiveRecord;
use humhub\components\behaviors\GUID;
use humhub\modules\user\models\User;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\web\Linkable;

use function min;

/**
 * This is the model class for the table "player_achievement".
 *
 * @package GameCenter/Models
 * @property string           $guid
 * @property int              $percent_completed A percentage value that states how far the player has progressed on the achievement.
 * @property int              $player_id         The identifier for the Player.
 * @property int              $achievement_id    The identifier for the Achievement Description.
 * @property string           $created_at
 * @property int              $created_by
 * @property string           $updated_at
 * @property int              $updated_by
 * @property-read Player      $player
 * @property-read Achievement $achievement
 * @property-read Game        $game
 * @mixin GUID
 */
#[Schema(properties: [
    new Property('guid', type: 'string', format: 'guid'),
    new Property('percentCompleted', type: 'integer', maximum: 100, minimum: 0),
    new Property('updatedAt', type: 'string', format: 'date-time'),
    new Property('player', type: 'integer'),
    new Property('isCompleted', type: 'boolean')
])]
class PlayerAchievement extends ActiveRecord implements Linkable
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
        'id'                => 'ID',
        'description'       => GameCenterModule::t('base', 'Achievement'),
        'percent_completed' => GameCenterModule::t('base', 'Percent completed'),
        'player'            => GameCenterModule::t('base', 'Player'),
        'lastReportedDate'  => GameCenterModule::t('base', 'Last Reported Date'),
        'isCompleted'       => GameCenterModule::t('base', 'Is Completed')
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
   * @inheritdoc
   * @return array<class-string>
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function behaviors(): array
  {
    return [GUID::class];
  }

  public function extraFields(): array
  {
    $extraFields = ['achievement'];

    return array_merge(parent::extraFields(), $extraFields);
  }

  /** @noinspection PhpMissingParentCallCommonInspection */
  public function fields(): array
  {
    return [
        'id'               => 'guid',
        'player'           => 'player_id',
        'percentCompleted' => 'percent_completed',
        'isComplete'       => function () {
          return $this->isCompleted();
        },
        'updatedAt'        => 'updated_at'
    ];
  }

  /**
   * A Boolean value that states whether the player has completed the achievement.
   *
   * @return bool
   */
  public function isCompleted(): bool
  {
    return $this->percent_completed == 100;
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

  public function isSecret(): bool
  {
    return $this->achievement->secret;
  }

  /**
   * getGame
   *
   * @return ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id'])
                ->via('achievement');
  }

  public function getLinks(): array
  {
    return [
      //Link::REL_SELF => Url::toLeaderboard($this->id),
        'description' => Url::toAchievement($this->achievement_id),
        'view'        => Url::toAchievements($this->achievement->game_id, $this->player_id)
    ];
  }

  /**
   * The player who earned the achievement.
   *
   * @return ActiveQuery
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

  public function getUrl(): string
  {
    return Url::toAchievements($this->game->id, $this->player->id);
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
        [['percent_completed'], 'default', 'value' => 0],
        [['guid'], 'string', 'max' => 45, 'min' => 2],
    ];
  }

  public function showProgress(): bool
  {
    return $this->achievement->show_progress;
  }

  public function updateProgress(int $progress): bool
  {
    if ($this->isCompleted() && $progress < 100) {
      Yii::error('Updating progress of a completed Achievement is not allowed!');

      return false;
    }
    $this->percent_completed = min(100, $progress);
    if ($progress == 100) {
      $this->sendNotification();
    }

    return $this->save(true, ['percent_completed']);
  }

  /**
   * @return void
   */
  private function sendNotification(): void
  {
    $user = User::findOne(['id' => $this->player_id]);
    try {
      AchievementUnlock::instance()
                       ->about($this)
                       ->create();
      AchievementUnlocked::instance()
                         ->about($this)
                         ->send($user);
    } catch (InvalidConfigException|Exception $e) {
      Yii::error('Could not send ', $e);
    }
  }

}
