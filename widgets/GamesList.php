<?php
/**
 * @author       Christian Seiler <christian@christianseiler.ch>
 * @since        1.0.0
 * @noinspection PhpMissingParentCallCommonInspection
 */

namespace fhnw\modules\gamecenter\widgets;

use yii\base\Widget;

/**
 * @package GameCenter/Widgets
 */
class GamesList extends Widget
{

  public array $games;
  /** @var bool $showMore */
  public bool $showMore;

  public function run()
  {
    return $this->render('gamesList', ['games' => $this->games, 'showMore' => $this->showMore]);
  }

}
