<?php

namespace fhnw\modules\gamecenter\models;

use Exception;
use fhnw\modules\gamecenter\events\PlayEvent\PlayEvent;
use humhub\components\ActiveRecord;
use humhub\components\behaviors\PolymorphicRelation;
use humhub\modules\user\components\ActiveQueryUser;
use humhub\modules\user\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

use function is_subclass_of;

/**
 * This is the model class for table "play".
 *
 * @property int $id
 * @property int $game_id
 * @property int $player_id
 * @property ?string $last_played
 * @property ?string $created_at
 * @property ?int $created_by
 * @property ?string $updated_at
 * @property ?int $updated_by
 * @property \fhnw\modules\gamecenter\models\Game $game
 * @property \fhnw\modules\gamecenter\models\Player $player
 * @package GameCenter/Models
 */
class Play extends ActiveRecord
{

  /**
   * @event \fhnw\modules\gamecenter\events\PlayEvent
   */
  public const EVENT_PLAY = 'play';

  /**
   * Returns a query searching for all container ids the user is following.
   * If $containerClass is given we only search for a certain container type.
   *
   * @param \fhnw\modules\gamecenter\models\Player $player
   * @param ?class-string $containerClass
   *
   * @return Query
   */
  public static function getPlayedContainerIdQuery(Player $player, string $containerClass = null): Query
  {
    $table = self::tableName();

    return (new Query())->select('contentcontainer.id AS id')
                        ->from($table)
                        ->innerJoin(
                          'contentcontainer',
                          'contentcontainer.pk = user_follow.object_id AND contentcontainer.class = user_follow.object_model'
                        )
                        ->where(['user_follow.user_id' => $player->id])
                        ->indexBy('id')
                        ->andWhere(
                          $containerClass
                            ? ["{$table}.object_model" => $containerClass]
                            : [
                            'OR',
                            ["{$table}.object_model" => Game::class],
                            ["{$table}.object_model" => User::class]
                          ]
                        );
  }

  /**
   * Returns all played games of the given user as ActiveQuery.
   *
   * @param Game $game
   *
   * @return ActiveQuery Game query of all played games
   */
  public static function getPlayedGamesQuery(Game $game): ActiveQuery
  {
    return self::find()
               ->where(['play.game_id' => $game->id]);
  }

  /**
   * Returns all active users following the given $target record.
   *
   * @param \humhub\components\ActiveRecord $target
   *
   * @return ActiveQueryUser
   */
  public static function getPlayersQuery(ActiveRecord $target): ActiveQueryUser
  {
    $table = self::tableName();
    $subQuery = self::find()
                    ->where(["{$table}.object_model" => get_class($target), "{$table}.object_id" => $target->getPrimaryKey()])
                    ->andWhere("{$table}.user_id=user.id");

    return User::find()
               ->visible()
               ->andWhere(['exists', $subQuery]);
  }

  /**
   * @inheritdoc
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return 'play';
  }

  /**
   * @param bool $insert
   * @param array $changedAttributes
   *
   * @return void
   */
  public function afterSave($insert, $changedAttributes): void
  {
    $this->trigger(
      Play::EVENT_PLAY,
      new PlayEvent(['user' => $this->player, 'target' => $this->getTarget()])
    );

    parent::afterSave($insert, $changedAttributes);
  }

  /** @return array */
  public function behaviors(): array
  {
    return [
      [
        'class'            => PolymorphicRelation::class,
        'mustBeInstanceOf' => [
          \yii\db\ActiveRecord::class,
        ]
      ]
    ];
  }

  /**
   * @return \yii\db\ActiveRecord|null
   */
  public function getTarget(): ?\yii\db\ActiveRecord
  {
    try {
      $targetClass = $this->object_model;
      if ($targetClass != '' && is_subclass_of($targetClass, \yii\db\ActiveRecord::class)) {
        return $targetClass::findOne(['id' => $this->object_id]);
      }
    }
    catch (Exception $e) {
      // Avoid errors in integrity check
      Yii::error($e);
    }

    return null;
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser(): ActiveQuery
  {
    return $this->hasOne(Player::class, ['id' => 'user_id']);
  }

  /** @return array
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function rules(): array
  {
    return [
      [['game_id', 'user_id'], 'required'],
      [['game_id', 'user_id'], 'integer'],
      [['last_played', 'created_at', 'updated_at'], 'safe']
    ];
  }

}
