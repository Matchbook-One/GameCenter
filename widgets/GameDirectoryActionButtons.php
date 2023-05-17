<?php
/**
 * @link      https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license   https://www.humhub.com/licences
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use humhub\components\Widget;
use humhub\widgets\Link;
use Yii;

/**
 */
class GameDirectoryActionButtons extends Widget
{
  public Game $game;

  /**
   * @var string $template Template for buttons
   */
  public string $template = '{buttons}';

  /**
   * @inheritdoc
   */
  public function run()
  {
    $html = '';
    /** @var \fhnw\modules\gamecenter\components\GameModule $module */
    $module = Yii::$app->getModule($this->game->module);
    if ($module) {
      $html = Link::primary('Play')
                  ->link($module->getGameUrl());
    }

    if (trim($html) === '') {
      return '';
    }

    return str_replace('{buttons}', $html, $this->template);
  }

}
