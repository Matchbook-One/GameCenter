<?php

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use humhub\components\Widget;

/**
 * @package GameCenter/Widgets
 */
class PlayButton extends Widget
{
  public Game $game;
  public $label = 'Play';
  public $playOptions = ['class' => 'btn btn-primary btn-sm'];

  public function run()
  {
  }
}