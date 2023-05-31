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
class GameDirectoryCard extends Widget
{

  /** @var \fhnw\modules\gamecenter\models\Game $game */
  public Game $game;

  /**
   * @var string HTML wrapper around card
   */
  public string $template = '<div class="card card-game col-lg-3 col-md-4 col-sm-6 col-xs-12">{card}</div>';

  /**
   * @inheritdoc
   */
  public function run()
  {
    $card = $this->render('gameDirectoryCard', ['game' => $this->game]);

    return str_replace('{card}', $card, $this->template);
  }

}
