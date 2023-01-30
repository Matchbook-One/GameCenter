<?php

/**
 * @author  christianseiler
 * @version 1.0
 * @package GameCenter
 */

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use Yii;
use yii\base\Component;

/**
 * The Class GameCenter
 */
class GameCenter extends Component {

  /**
   * @param string $module the Module ID
   * @param array  $config the configuration
   *
   * @return bool
   */
  public static function register(string $module, array $config): bool {
    Yii::debug("Register module {$module}", __METHOD__);
    $game = new Game();
    $game->module = $module;

    $game->title = $config['title'];
    $game->description = $config['description'];

    return $game->save();
  }

  /**
   * unregister
   *
   * @static
   *
   * @param string $module the Module ID
   *
   * @return bool
   */
  public static function unregister(string $module): bool {

    /** @var Game $game */
    $game = Game::findOne(['module' => $module]);
    if ($game) {
      $game->status = Game::STATUS_DISABLED;

      return $game->save();
    }

    return false;
  }
}