<?php

namespace fhnw\modules\gamecenter\widgets;

use humhub\components\Widget;

class LeaderboardScore extends Widget
{

  public string $player;
  public int $rank;
  public int $score;

  public function run()
  {
    return $this->render('leaderboardScore', ['rank' => $this->rank, 'score' => $this->score, 'player' => $this->player]);
  }

}