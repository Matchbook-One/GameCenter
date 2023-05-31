<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use fhnw\modules\gamecenter\components\LeaderboardType;
use fhnw\modules\gamecenter\components\Period;
use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\helpers\DateTime;
use fhnw\modules\gamecenter\helpers\Url;
use humhub\components\ActiveRecord;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use yii\db\ActiveQuery;
use yii\web\Link;
use yii\web\Linkable;

use function array_merge;

/**
 * This is the model class for the table “leaderboard”.
 *
 * @package GameCenter/Models
 * @property      int  $id
 * @property      int  $type The leaderboard's type: classic or recurring.
 * @property      int  $game_id
 * @property-read Game $game
 */
#[Schema(
    properties: [
        new Property('id', type: 'integer'),
        new Property('type', type: 'integer', enum: LeaderboardType::class),
        new Property('game_id', type: 'integer')
    ]
)]
class Leaderboard extends ActiveRecord implements Linkable
{

  public const DEFAULT_LIMIT = 10;

  //  #[Property]
  //  public string $game;
  //  #[Property(format: 'date-time')]
  //  public string $lastUpdated;
  //  #[Property(maximum: 100, minimum: 0)]
  //public int $percentCompleted;

  /**
   * @inheritdoc
   * @static
   * @return       string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public static function tableName(): string
  {
    return 'leaderboard';
  }

  public function extraFields(): array
  {
    $fields = parent::extraFields();
    $extraFields = ['game'];

    return array_merge($fields, $extraFields);
  }

  public function fields(): array
  {
    $fields = parent::fields();
    $extraFields = [];

    return array_merge($fields, $extraFields);
  }

  /**
   * @return ActiveQuery
   */
  public function getGame(): ActiveQuery
  {
    return $this->hasOne(Game::class, ['id' => 'game_id']);
  }

  public function getLinks()
  {
    return [
        Link::REL_SELF => Url::toLeaderboard($this->id),
        'view'         => Url::toLeaderboards($this->game_id)
    ];
  }

  /**
   * @param ?int $limit
   *
   * @return array<Score>
   */
  public function getScores(?int $limit = self::DEFAULT_LIMIT): array
  {
    $scores = Score::find()
                   ->where(['game_id' => $this->game_id]);
    if ($this->getType() !== LeaderboardType::CLASSIC) {
      $scores->andWhere(
          [
              '>=',
              'timestamp',
              DateTime::formatted(
                  $this->getCurrentPeriod()
                       ->getStart()
              )
          ]
      );
    }

    return $scores->orderBy(['score' => SORT_DESC])
                  ->limit($limit)
                  ->all();
  }

  public function getType(): LeaderboardType
  {
    return LeaderboardType::from($this->type);
  }

  /**
   * @return Period|null
   */
  public function getCurrentPeriod(): ?Period
  {
    return match ($this->getType()) {
      LeaderboardType::RECURRING_DAILY   => Period::daily(),
      LeaderboardType::RECURRING_WEEKLY  => Period::weekly(),
      LeaderboardType::RECURRING_MONTHLY => Period::month(),
      LeaderboardType::CLASSIC           => null,
    };
  }

  public function getTitle(): string
  {
    return match ($this->getType()) {
      LeaderboardType::RECURRING_DAILY   => GameCenterModule::t('leaderboard', 'Daily Leaderboard'),
      LeaderboardType::RECURRING_WEEKLY  => GameCenterModule::t('leaderboard', 'Weekly Leaderboard'),
      LeaderboardType::RECURRING_MONTHLY => GameCenterModule::t('leaderboard', 'Monthly Leaderboard'),
      LeaderboardType::CLASSIC           => GameCenterModule::t('leaderboard', 'All time Leaderboard'),
    };
  }

}

