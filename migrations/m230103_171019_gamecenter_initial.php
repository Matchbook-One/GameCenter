<?php

/** @noinspection PhpMissingParentCallCommonInspection */

use yii\db\Migration;

/**
 * Class m230103_171019_gamecenter_initial
 */
class m230103_171019_gamecenter_initial extends Migration
{
  const ACHIEVEMENT = 'achievement';

  const PLAYER_ACHIEVEMENT = 'player_achievement';

  const GAME = 'game';

  const SCORE = 'score';

  const GAME_TAG = 'game_tag';

  const LEADERBOARD = 'leaderboard';

  const USER = 'user';

  const GAME_REPORT = 'game_report';

  /**
   * {@inheritdoc}
   * @return bool
   */
  public function safeDown(): bool
  {
    $this->dropForeignKey('fk_achievement_game', self::ACHIEVEMENT);
    $this->dropForeignKey('fk_pa_achievement', self::PLAYER_ACHIEVEMENT);
    $this->dropForeignKey('fk_pa_player', self::PLAYER_ACHIEVEMENT);

    $this->dropTable(self::PLAYER_ACHIEVEMENT);
    $this->dropTable(self::GAME);
    $this->dropTable(self::PLAYER_ACHIEVEMENT);

    return true;
  }

  /**
   * {@inheritdoc}
   * @return void
   */
  public function safeUp(): void
  {
    $columns = [
      'id'          => $this->primaryKey(),
      'guid'        => $this->string()->notNull()->unique(),
      'module'      => $this->string()->notNull()->unique(),
      'title'       => $this->string()->notNull(),
      'description' => $this->string()->defaultValue('')->notNull(),
      'status'      => $this->tinyInteger()->notNull(),
      'created_at'  => $this->dateTime()->notNull(),
      'created_by'  => $this->integer()->notNull(),
      'updated_at'  => $this->dateTime()->notNull(),
      'updated_by'  => $this->integer()->notNull()
    ];
    $this->createTable(self::GAME, $columns);

    $columns = [
      'id'          => $this->primaryKey(),
      'guid'        => $this->string()->notNull()->unique(),
      'name'        => $this->string()->notNull(),
      'title'       => $this->string()->notNull(),
      'description' => $this->string()->notNull(),
      'image'       => $this->string()->notNull(),
      'game_id'     => $this->integer()->notNull(),
      'created_at'  => $this->dateTime()->notNull(),
      'created_by'  => $this->integer()->notNull(),
      'updated_at'  => $this->dateTime()->notNull(),
      'updated_by'  => $this->integer()->notNull()
    ];
    $this->createTable(self::ACHIEVEMENT, $columns);

    $columns = [
      'player_id'         => $this->integer()->notNull(),
      'achievement_id'    => $this->integer()->notNull(),
      'percent_completed' => $this->integer()->defaultValue(0),
      'created_at'        => $this->dateTime()->notNull(),
      'created_by'        => $this->integer()->notNull(),
      'updated_at'        => $this->dateTime()->notNull(),
      'updated_by'        => $this->integer()->notNull(),
      'PRIMARY KEY(player_id, achievement_id)'
    ];
    $this->createTable(self::PLAYER_ACHIEVEMENT, $columns);

    $columns = [
      'id'        => $this->primaryKey(),
      'game_id'   => $this->integer()->notNull(),
      'player_id' => $this->integer()->notNull(),
      'score' => $this->integer()->notNull(),
      'timestamp' => $this->dateTime()->notNull()
    ];
    $this->createTable(self::SCORE, $columns);

    $columns = [
      'game_id' => $this->integer()->notNull(),
      'tag'     => $this->string()->notNull(),
      'PRIMARY KEY(game_id, tag)'
    ];
    $this->createTable(self::GAME_TAG, $columns);

    $columns = [
      'id'        => $this->primaryKey(),
      'game_id'   => $this->integer()->notNull(),
      'player_id' => $this->integer()->notNull(),
      'option'    => $this->string()->notNull(),
      'value'     => $this->string()->notNull(),
      'timestamp' => $this->dateTime()->notNull()
    ];
    $this->createTable(self::GAME_REPORT, $columns);

    $columns = [
      'id'      => $this->primaryKey(),
      'game_id' => $this->integer()->notNull(),
      'type'    => $this->integer()->notNull()
    ];
    $this->createTable(self::LEADERBOARD, $columns);

    $this->addForeignKey('achievement_game_id_fk', self::ACHIEVEMENT, 'game_id', self::GAME, 'id');
    $this->addForeignKey('playerachievement_user_id_fk', self::PLAYER_ACHIEVEMENT, 'player_id', self::USER, 'id');
    $this->addForeignKey('playerachievement_achievement_id_fk', self::PLAYER_ACHIEVEMENT, 'achievement_id', self::ACHIEVEMENT, 'id');
    $this->addForeignKey('gametag_game_id_fk', self::GAME_TAG, 'game_id', self::GAME, 'id');
    $this->addForeignKey('score_game_id_fk', self::SCORE, 'game_id', self::GAME, 'id');
    $this->addForeignKey('score_user_id_fk', self::SCORE, 'player_id', self::USER, 'id');
    $this->addForeignKey('report_game_id_fk', self::GAME_REPORT, 'game_id', self::GAME, 'id');
    $this->addForeignKey('report_user_id_fk', self::GAME_REPORT, 'player_id', self::USER, 'id');
  }
}
