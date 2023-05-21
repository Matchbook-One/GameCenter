<?php

/**
 * GameModule.php
 *
 * @package GameCenter/Components
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\Leaderboard;
use fhnw\modules\gamecenter\models\Player;
use humhub\components\Module;
use Yii;

/**
 * The GameModule Class
 * @phpstan-type GameConfig array{title: non-empty-string, description: non-empty-string, tags: array<string> }
 * @phpstan-type AchievementConfig array{name: string, title: string, description: string, image: ?string}
 *
 * @property-read GameConfig $gameConfig
 * @property-read AchievementConfig[] $achievementConfig
 */
abstract class GameModule extends Module
{

  final public function hasAchievements(): bool { return $this->getAchievementConfig() !== []; }

  final public function hasLeaderboards(): bool { return $this->getLeaderBoardConfig() !== []; }

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
   * @phpstan-return GameConfig
   * @return array
   */
  abstract public function getGameConfig(): array;

  /**
   * @return string
   */
  abstract public function getGameUrl(): string;

  /**
   * @phpstan-return array<Leaderboard::CLASSIC|Leaderboard::RECURRING_DAILY|Leaderboard::RECURRING_WEEKLY|Leaderboard::RECURRING_MONTHLY>
   */
  abstract public function getLeaderboardConfig(): array;

  public function getPlayer(): Player
  {
    $player_id = Yii::$app->user->id;
    $player = Player::findOne(['id' => $player_id]);

    return $player;
  }

}
