<?php

/**
 * @module  GameCenter
 * @author  Christian Seiler
 * @version 1.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Achievement;
use humhub\modules\content\components\ContentContainerModule;

/**
 * The GameModule Class
 */
abstract class GameModule extends ContentContainerModule {

  /**
   * @return Achievement[]
   */
  abstract public function getAchievementConfig(): array;

  /**
   * @return array
   */
  abstract public function getGameCenterConfig(): array;

  /**
   * @return LeaderBoard[]
   */
  abstract public function getLeaderBoardConfig(): array;
}