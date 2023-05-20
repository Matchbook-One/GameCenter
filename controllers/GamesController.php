<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\components\ActiveQueryGame;
use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\Achievement;
use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\PlayerAchievement;

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
   * @throws \yii\web\HttpException
   */
  public function init(): void
  {
    parent::init();
    $this->setActionTitles(['gamecenter' => GameCenterModule::t('base', 'Games'),]);
  }

  public function actionAchievements($gid, $pid): string
  {
    $game = Game::findOne(['id' => $gid]);
    $join = sprintf("%s.id = %s.achievement_id", Achievement::tableName(), PlayerAchievement::tableName());
    $achievements = PlayerAchievement::find()
                                     ->leftJoin(Achievement::tableName(), $join)
                                     ->where([Achievement::tableName() . '.game_id' => $gid])
                                     ->andWhere([PlayerAchievement::tableName() . '.player_id' => $pid])
                                     ->all();

    return $this->render('achievements', ['game' => $game, 'achievements' => $achievements]);
  }

  protected function getPaginationQuery(): ActiveQueryGame
  {
    $pageQuery = Game::find();

    $pageQuery->orderBy(['title' => SORT_ASC]);

    return $pageQuery;
  }

  /**
   * @param \yii\base\Model[] $items
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
