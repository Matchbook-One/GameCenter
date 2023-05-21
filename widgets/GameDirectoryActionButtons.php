<?php
/**
 * @link      https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license   https://www.humhub.com/licences
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\components\GameModule;
use fhnw\modules\gamecenter\controllers\ContentController;
use fhnw\modules\gamecenter\helpers\Url;
use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\Player;
use humhub\components\Widget;
use humhub\widgets\Button;
use Yii;

/**
 */
class GameDirectoryActionButtons extends Widget
{

  public Game $game;

  /** @var string $template Template for buttons */
  public string $template = '<div class="btn-group" role="group">{buttons}</div>';

  /**
   * @inheritdoc
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run()
  {
    $module = $this->game->getModule();
    $html = [];

    $html[] = $this->getPlayLink($module);
    if ($module->hasLeaderboards()) {
      $html[] = $this->getLeaderBoardLink($this->game);
    }
    if ($module->hasAchievements()) {
      $html[] = $this->getAchievementsLink($this->game, $module->getPlayer());
    }
    $html = join($html);

    return str_replace('{buttons}', $html, $this->template);
  }

  private function getAchievementsLink(Game $game, Player $player): Button
  {
    return Button::defaultType('Achievements')
                 ->link(Url::toAchievements($game, $player));
  }

  private function getController(): ContentController
  {
    /** @var ContentController $controller */
    $controller = Yii::$app->controller;

    return $controller;
  }

  private function getLeaderBoardLink(Game $game): Button
  {
    return Button::defaultType('Leaderboards')
                 ->link(Url::toLeaderboards($game->id));
  }

  private function getPlayLink(GameModule $module): Button
  {
    return Button::primary('Play')
                 ->link($module->getGameUrl());
  }

}
