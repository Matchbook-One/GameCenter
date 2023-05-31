<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\components\ActiveQueryGame;
use fhnw\modules\gamecenter\components\ContentController;
use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\Achievement;
use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\Leaderboard;
use fhnw\modules\gamecenter\models\PlayerAchievement;
use yii\base\Model;
use yii\web\HttpException;

/**
 * IndexController
 *
 * @package GameCenter/Controllers
 */
class GamesController extends ContentController
{

  /**
   * @inheritdoc
   * @return void
   * @throws HttpException
   */
  public function init(): void
  {
    parent::init();
    $this->setActionTitles(['gamecenter' => GameCenterModule::t('base', 'Games'),]);
  }

  /**
   * @param int $game
   * @param int $player
   *
   * @return string
   */
  public function actionAchievements(int $game, int $player): string
  {
    $g = Game::findOne(['id' => $game]);
    $join = sprintf('%s.id = %s.achievement_id', Achievement::tableName(), PlayerAchievement::tableName());
    $achievements = PlayerAchievement::find()
                                     ->leftJoin(Achievement::tableName(), $join)
                                     ->where([Achievement::tableName() . '.game_id' => $game])
                                     ->andWhere([PlayerAchievement::tableName() . '.player_id' => $player])
                                     ->all();

    return $this->render('achievements', ['game' => $g, 'achievements' => $achievements]);
  }

  /**
   * @param int $game
   *
   * @return string
   */
  public function actionLeaderboard(int $game): string
  {
    $game = Game::findOne(['id' => $game]);
    $leaderboards = Leaderboard::find()
                               ->where(['game_id' => $game->id])
                               ->all();

    return $this->render('leaderboards', ['game' => $game, 'leaderboards' => $leaderboards]);
  }

  /**
   * @return ActiveQueryGame
   */
  protected function getPaginationQuery(): ActiveQueryGame
  {
    $pageQuery = Game::find();

    $pageQuery->orderBy(['title' => SORT_ASC]);

    return $pageQuery;
  }

  /**
   * @param Model[] $items
   *
   * @return string
   */
  protected function renderItems(array $items): string
  {
    return $this->render(
        'index',
        [
            'games'    => $items,
            'showMore' => !$this->isLastPage()
        ]
    );
  }

}
