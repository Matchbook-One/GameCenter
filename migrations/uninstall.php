<?php

/**
 * @package GameCenter
 * @since   1.0.0
 * @author  Christian Seiler <christian@christianseiler.ch>
 */

use yii\db\Migration;

/**
 * Class uninstall
 *
 * @package    GameCenter
 * @since      1.0.0
 * @author     Christian Seiler <christian@christianseiler.ch>
 */
class uninstall extends Migration
{

  /**
   * @inerhitdoc
   */
  public function down()
  {
    echo "uninstall does not support migration down.\n";

    return false;
  }

  /**
   * @inerhitdoc
   */
  public function up()
  {
    $this->dropForeignKey('fk_achievement_game', 'achievement');
    $this->dropForeignKey('fk_game_genre', 'game');
    $this->dropForeignKey('fk_pa_achievement', 'player_achievement');
    $this->dropForeignKey('fk_pa_player', 'player_achievement');

    $this->dropTable('achievement');
    $this->dropTable('game');
    $this->dropTable('genre');
    $this->dropTable('player_achievement');
  }

}
