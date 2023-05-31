<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use humhub\components\Widget;

/**
 * @package GameCenter/Widgets
 */
class PlayButton extends Widget
{

  public Game $game;
  public string $label = 'Play';
  public array $playOptions = ['class' => 'btn btn-primary btn-sm'];

  public function run() {}

}
