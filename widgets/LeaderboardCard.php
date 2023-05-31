<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use Exception;
use fhnw\modules\gamecenter\components\LeaderboardType;
use fhnw\modules\gamecenter\models\Leaderboard;
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
    } catch (Exception $e) {
      Yii::error($e);
    }

    return '';
  }

  private function getWidgetOptions(): array
  {
    return [
        'leaderboard' => $this->leaderboard,
        'id'          => $this->id
    ];
  }

  /**
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run(): string
  {
    $config = [
        'scores' => $this->leaderboard->getScores(),
        'title'  => $this->leaderboard->getTitle(),
    ];

    if ($this->leaderboard->getType() !== LeaderboardType::CLASSIC) {
      $config['period'] = $this->leaderboard->getCurrentPeriod();
    }

    return $this->render('leaderboardCard', $config);
  }

}
