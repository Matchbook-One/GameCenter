<?php
/**
 * @link      https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license   https://www.humhub.com/licences
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\Game;
use humhub\components\Widget;

/**
 */
class GameDirectoryStatus extends Widget
{
  public Game $game;

  /**
   * @inheritdoc
   */
  public function run()
  {
    if ($this->game->isArchived()) {
      return $this->render('gameDirectoryStatus', [
        'class' => 'label label-primary',
        'text'  => GameCenterModule::t('base', 'Archived'),
      ]);
    }
  }

}
