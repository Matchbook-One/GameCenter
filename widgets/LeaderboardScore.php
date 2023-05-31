<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use humhub\components\Widget;

class LeaderboardScore extends Widget
{

  public string $date;
  public string $player;
  public int $rank;
  public int $score;

  /** @noinspection PhpMissingParentCallCommonInspection */

  public function run()
  {
    return $this->render(
        'leaderboardScore',
        ['rank' => $this->rank, 'score' => $this->score, 'player' => $this->player, 'date' => $this->date]
    );
  }

}
