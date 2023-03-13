<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\AchievementDescription;
use fhnw\modules\gamecenter\models\Game;
use Yii;
use yii\base\Component;

/**
 * The Class GameCenter
 * @phpstan-import-type GameConfig from GameModule
 * @phpstan-import-type AchievementConfig from GameModule
 */
class GameCenter extends Component
{

    /** @var GameCenter $instance */
    private static $instance;

    /**
     * @return GameCenter
     * @static
     */
    public static function getInstance(): GameCenter
    {
        if (self::$instance === null) {
            self::$instance = new GameCenter();
        }

        return self::$instance;
    }

    /**
     * @param string     $moduleID the Module ID
     * @param GameModule $module   the module
     *
     * @return boolean
     */
    public function register(string $moduleID, GameModule $module): bool
    {
        Yii::debug("Register module $moduleID", __METHOD__);
        $gameConfig   = $module->getGameConfig();
        $game         = self::registerGame($moduleID, $gameConfig);
        $achievements = $module->getAchievementConfig();
        $this->registerAchievements($game, $achievements);

        return true;
    }

    /**
     * unregister
     *
     * @param string $module the Module ID
     *
     * @return bool
     */
    public function unregister(string $module): bool
    {
        $game = Game::findOne(['module' => $module]);
        if ($game) {
            $game->status = Game::STATUS_DISABLED;

            return $game->save();
        }

        return false;
    }

    /**
     * @param Game                $game         the Game id
     * @param AchievementConfig[] $achievements The Configs
     *
     * @return void
     */
    private function registerAchievements(Game $game, $achievements): void
    {
        foreach ($achievements as $config) {
            /** @var AchievementConfig $config */
            $achievement = new AchievementDescription($config);
            $achievement->link('game', $game);
            $achievement->save();
        }
    }

    /**
     * @param string     $module
     * @param GameConfig $config
     *
     * @return Game
     */
    private function registerGame(string $module, $config): Game
    {
        $game = Game::findOne(['module' => $module]);
        if (!$game) {
            $game         = new Game($config);
            $game->module = $module;
            $game->save();
        }

        return $game;
    }
}
