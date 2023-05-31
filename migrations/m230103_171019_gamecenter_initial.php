<?php

/**
 * @noinspection PhpIllegalPsrClassPathInspection
 * @noinspection PhpMissingParentCallCommonInspection
 */

use fhnw\modules\gamecenter\models\Achievement;
use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\GameTag;
use fhnw\modules\gamecenter\models\Leaderboard;
use fhnw\modules\gamecenter\models\Play;
use fhnw\modules\gamecenter\models\PlayerAchievement;
use fhnw\modules\gamecenter\models\Report;
use fhnw\modules\gamecenter\models\Score;
use humhub\modules\user\models\User;
use yii\db\Migration;

/**
 * Class m230103_171019_gamecenter_initial
 */
class m230103_171019_gamecenter_initial extends Migration
{

  /**
   * {@inheritdoc}
   * @return bool
   */
  public function safeDown(): bool
  {
    $this->dropForeignKey('fk_achievement_game', Achievement::tableName());
    $this->dropForeignKey('fk_pa_achievement', PlayerAchievement::tableName());
    $this->dropForeignKey('fk_pa_player', PlayerAchievement::tableName());

    $this->dropTable(PlayerAchievement::tableName());
    $this->dropTable(Game::tableName());
    $this->dropTable(Achievement::tableName());

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
        'guid'        => $this->string()
                              ->notNull()
                              ->unique(),
        'module'      => $this->string()
                              ->notNull()
                              ->unique(),
        'title'       => $this->string()
                              ->notNull(),
        'description' => $this->string()
                              ->defaultValue('')
                              ->notNull(),
        'status'      => $this->tinyInteger()
                              ->notNull(),
        'created_at'  => $this->dateTime()
                              ->notNull(),
        'created_by'  => $this->integer()
                              ->notNull(),
        'updated_at'  => $this->dateTime()
                              ->notNull(),
        'updated_by'  => $this->integer()
                              ->notNull()
    ];
    $this->createTable(Game::tableName(), $columns);

    $columns = [
        'id'            => $this->primaryKey(),
        'guid'          => $this->string()
                                ->notNull()
                                ->unique(),
        'name'          => $this->string()
                                ->notNull(),
        'title'         => $this->string()
                                ->notNull(),
        'description'   => $this->string()
                                ->notNull(),
        'secret'        => $this->boolean()
                                ->defaultValue(false),
        'show_progress' => $this->boolean()
                                ->defaultValue(false),
        'image'         => $this->string()
                                ->notNull(),
        'game_id'       => $this->integer()
                                ->notNull(),
        'created_at'    => $this->dateTime()
                                ->notNull(),
        'created_by'    => $this->integer()
                                ->notNull(),
        'updated_at'    => $this->dateTime()
                                ->notNull(),
        'updated_by'    => $this->integer()
                                ->notNull()
    ];
    $this->createTable(Achievement::tableName(), $columns);

    $columns = [
        'guid'              => $this->string()
                                    ->notNull(),
        'player_id'         => $this->integer()
                                    ->notNull(),
        'achievement_id'    => $this->integer()
                                    ->notNull(),
        'percent_completed' => $this->integer()
                                    ->defaultValue(0),
        'created_at'        => $this->dateTime()
                                    ->notNull(),
        'created_by'        => $this->integer()
                                    ->notNull(),
        'updated_at'        => $this->dateTime()
                                    ->notNull(),
        'updated_by'        => $this->integer()
                                    ->notNull(),
        'PRIMARY KEY(player_id, achievement_id)'
    ];
    $this->createTable(PlayerAchievement::tableName(), $columns);

    $columns = [
        'id'        => $this->primaryKey(),
        'game_id'   => $this->integer()
                            ->notNull(),
        'player_id' => $this->integer()
                            ->notNull(),
        'score'     => $this->integer()
                            ->notNull(),
        'timestamp' => $this->dateTime()
                            ->notNull()
    ];
    $this->createTable(Score::tableName(), $columns);

    $columns = [
        'game_id' => $this->integer()
                          ->notNull(),
        'tag'     => $this->string()
                          ->notNull(),
        'PRIMARY KEY(game_id, tag)'
    ];
    $this->createTable(GameTag::tableName(), $columns);

    $columns = [
        'id'        => $this->primaryKey(),
        'game_id'   => $this->integer()
                            ->notNull(),
        'player_id' => $this->integer()
                            ->notNull(),
        'option'    => $this->string()
                            ->notNull(),
        'value'     => $this->string()
                            ->notNull(),
        'timestamp' => $this->dateTime()
                            ->notNull()
    ];
    $this->createTable(Report::tableName(), $columns);

    $columns = [
        'id'      => $this->primaryKey(),
        'game_id' => $this->integer()
                          ->notNull(),
        'type'    => $this->integer()
                          ->notNull()
    ];
    $this->createTable(Leaderboard::tableName(), $columns);

    $columns = [
        'id'          => $this->primaryKey(),
        'game_id'     => $this->integer()
                              ->notNull(),
        'player_id'   => $this->integer()
                              ->notNull(),
        'last_played' => $this->dateTime()
                              ->notNull(),
        'created_at'  => $this->dateTime()
                              ->notNull(),
        'created_by'  => $this->integer()
                              ->notNull(),
        'updated_at'  => $this->dateTime()
                              ->notNull(),
        'updated_by'  => $this->integer()
                              ->notNull(),

    ];
    $this->createTable(Play::tableName(), $columns);

    $this->addForeignKey('achievement_game_id_fk', Achievement::tableName(), 'game_id', Game::tableName(), 'id');
    $this->addForeignKey('playerachievement_user_id_fk', PlayerAchievement::tableName(), 'player_id', User::tableName(), 'id');
    $this->addForeignKey(
        'playerachievement_achievement_id_fk',
        PlayerAchievement::tableName(),
        'achievement_id',
        Achievement::tableName(),
        'id'
    );
    $this->addForeignKey('gametag_game_id_fk', GameTag::tableName(), 'game_id', Game::tableName(), 'id');
    $this->addForeignKey('score_game_id_fk', Score::tableName(), 'game_id', Game::tableName(), 'id');
    $this->addForeignKey('score_user_id_fk', Score::tableName(), 'player_id', User::tableName(), 'id');
    $this->addForeignKey('report_game_id_fk', Report::tableName(), 'game_id', Game::tableName(), 'id');
    $this->addForeignKey('report_user_id_fk', Report::tableName(), 'player_id', User::tableName(), 'id');
    $this->addForeignKey('play_user_id_fk', Play::tableName(), 'player_id', User::tableName(), 'id');
    $this->addForeignKey('play_game_id_fk', Play::tableName(), 'game_id', User::tableName(), 'id');
  }

}
