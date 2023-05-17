<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use humhub\components\Module;

/**
 * The GameModule Class
 * @phpstan-type GameConfig array{title: non-empty-string, description: non-empty-string, tags: array<string> }
 * @phpstan-type AchievementConfig array{name: string, title: string, description: string, image: ?string}
 * @phpstan-type LeaderboardConfig array{id: string, title: string, description: string, image: ?string}
 *
 * @property-read GameConfig $gameConfig
 * @property-read AchievementConfig[] $achievementConfig
 * @package GameCenter/Components
 */
abstract class GameModule extends Module
{
  /**
   * @return AchievementConfig[]
   */
  abstract public function getAchievementConfig(): array;

  /**
   * @return \fhnw\modules\gamecenter\models\Game
   */
  public function getGame(): Game
  {
    return Game::findOne(['module' => $this->id]);
  }

  /**
   * @phpstan-return LeaderboardConfig[]
   */
  //abstract public function getLeaderboardConfig(): array;

  /**
   * @return GameConfig
   */
  abstract public function getGameConfig(): array;

  /**
   * @return string
   */
  abstract public function getGameUrl(): string;
}
