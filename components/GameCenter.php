<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Achievement;
use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\GameTag;
use fhnw\modules\gamecenter\models\Leaderboard;
use Yii;
use yii\base\Component;

/**
 * The Class GameCenter
 * @phpstan-import-type GameConfig from GameModule
 * @phpstan-import-type AchievementConfig from GameModule
 *
 * @package GameCenter/Components
 */
class GameCenter extends Component
{

  /** @var GameCenter $instance */
  private static GameCenter $instance;

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
   * @param string $moduleID the Module ID
   * @param GameModule $module the module
   *
   * @return bool
   */
  public function register(string $moduleID, GameModule $module): bool
  {
    Yii::debug("Register module $moduleID", __METHOD__);
    $gameConfig = $module->getGameConfig();
    $game = self::registerGame($moduleID, $gameConfig);
    $achievements = $module->getAchievementConfig();
    $this->registerAchievements($game, $achievements);
    $leaderboards = $module->getLeaderboardConfig();
    $this->registerLeaderboards($game, $leaderboards);

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
   * @param Game $game the Game ID
   * @param AchievementConfig[] $achievements The Configs
   *
   * @return void
   */
  private function registerAchievements(Game $game, array $achievements): void
  {
    foreach ($achievements as $config) {
      $achievement = new Achievement($config);
      $achievement->link('game', $game);
      $achievement->save();
    }
  }

  /**
   * @param string $module
   * @param GameConfig $config
   *
   * @return Game
   */
  private function registerGame(string $module, $config): Game
  {
    $game = Game::findOne(['module' => $module]);
    if (!$game) {
      $game = new Game($config);
      $game->module = $module;
      $game->save();
      foreach ($config->tags as $name) {
        $tag = new GameTag();
        $tag->tag = $name;
        $tag->link('game', $game);
        $tag->save();
      }
    }

    return $game;
  }

  private function registerLeaderboards(Game $game, $leaderboards): void
  {
    foreach ($leaderboards as $boardType) {
      $board = new Leaderboard();
      $board->type = $boardType;
      $board->link('game', $game);
    }
  }

}
