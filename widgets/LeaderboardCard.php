<?php

namespace fhnw\modules\gamecenter\widgets;

use Exception;
use fhnw\modules\gamecenter\helpers\DateTime;
use fhnw\modules\gamecenter\models\Leaderboard;
use fhnw\modules\gamecenter\models\Score;
use humhub\components\Widget;
use Yii;

/**
 * @package GameCenter/Widgets
 */
class LeaderboardCard extends Widget
{

  private const LIMIT = 10;
  public Leaderboard $leaderboard;

  /**
   * @param \fhnw\modules\gamecenter\models\Leaderboard $leaderboard
   *
   * @return static
   * @static
   */
  public static function withBoard(Leaderboard $leaderboard): static
  {
    return new static(['leaderboard' => $leaderboard]);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    try {
      $result = $this::widget($this->getWidgetOptions());

      return $result ?: '';
    }
    catch (Exception $e) {
      Yii::error($e);
    }

    return '';
  }

  /**
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run(): string
  {
    $config = [
      'scores' => $this->getScores(leaderboard: $this->leaderboard),
      'title'  => $this->leaderboard->getTitle(),
    ];

    if ($this->leaderboard->type !== Leaderboard::CLASSIC) {
      $config['period'] = $this->leaderboard->getCurrentPeriod();
    }

    return $this->render('leaderboardCard', $config);
  }

  /**
   * @param \fhnw\modules\gamecenter\models\Leaderboard $leaderboard
   *
   * @return array<Score>
   */
  private function getScores(Leaderboard $leaderboard): array
  {
    $scores = Score::find()
                   ->where(['game_id' => $leaderboard->game_id]);
    if ($leaderboard->type !== Leaderboard::CLASSIC) {
      $scores->andWhere(
        [
          '>=',
          'timestamp',
          DateTime::formatted(
            $leaderboard->getCurrentPeriod()
                        ->getStart()
          )
        ]
      );
    }

    return $scores->orderBy(['score' => SORT_DESC])
                  ->limit(self::LIMIT)
                  ->all();
  }

  private function getWidgetOptions(): array
  {
    return [
      'leaderboard' => $this->leaderboard,
      'id'          => $this->id
    ];
  }

}