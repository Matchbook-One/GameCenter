<?php

/**
 * @module  GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use humhub\modules\content\components\ContentContainerModule;

/**
 * The GameModule Class
 * @phpstan-type GameConfig array{title: non-empty-string, description: non-empty-string, tags: array<string> }
 * @phpstan-type AchievementConfig array{name: string, title: string, description: string, image: ?string}
 * @phpstan-type LeaderboardConfig array{id: string, title: string, description: string, image: ?string}
 *
 * @property-read GameConfig          $gameConfig
 * @property-read AchievementConfig[] $achievementConfig
 */
abstract class GameModule extends ContentContainerModule
{
    /**
     * @return AchievementConfig[]
     */
    abstract public function getAchievementConfig();

    /**
     * @return GameConfig
     */
    abstract public function getGameConfig();

    /**
     * @phpstan-return LeaderboardConfig[]
     */
    //abstract public function getLeaderboardConfig(): array;
}
