<?php

/**
 * GameModule.php
 *
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\Player;
use humhub\components\Module;
use JetBrains\PhpStorm\ArrayShape;
use Yii;

/**
 * The GameModule Class
 * @phpstan-type GameConfig array{title: non-empty-string, description: non-empty-string, tags: array<string> }
 * @phpstan-type AchievementConfig array{
 *   name: string,
 *   title: string,
 *   description: string,
 *   secret: ?bool,
 *   show_progress: ?bool,
 *   image: ?string
 * }
 *
 * @property-read GameConfig          $gameConfig
 * @property-read AchievementConfig[] $achievementConfig
 * @package GameCenter/Components
 */
abstract class GameModule extends Module
{

  /**
   * @returns array<{name: string, title: string, description: string, secret?: bool, show_progress?: bool}>
   */
  #[ArrayShape([['name' => 'string', 'title' => 'string', 'description' => 'string', 'secret' => 'bool', 'show_progress' => 'bool']])]
  abstract public function getAchievementConfig(): array;

  /**
   * @phpstan-return GameConfig
   * @return array
   */
  #[ArrayShape(['title' => 'string', 'description' => 'string', 'tags' => 'string[]'])]
  abstract public function getGameConfig(): array;

  /**
   * @return string
   */
  abstract public function getGameUrl(): string;

  /**
   * @return array<LeaderboardType>
   */
  abstract public function getLeaderboardConfig(): array;

  /**
   * @return bool
   */
  final public function hasAchievements(): bool
  {
    return $this->getAchievementConfig() !== [];
  }

  /**
   * @return bool
   */
  final public function hasLeaderboards(): bool
  {
    return $this->getLeaderboardConfig() !== [];
  }

  /**
   * @return Game
   */
  public function getGame(): Game
  {
    return Game::findOne(['module' => $this->id]);
  }

  /**
   * @return Player
   */
  public function getPlayer(): Player
  {
    $player_id = Yii::$app->user->id;

    return Player::findOne(['id' => $player_id]);
  }

}
