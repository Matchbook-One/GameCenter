<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\models\{Game, Score};
use Yii;

/**
 * Class ScoreController
 *
 * @package GameCenter
 */
class ScoreController extends RestController
{

  /**
   * Creates a score
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  public function actionCreate()
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;

    $game = Game::findOne(['module' => $request->post('module')]);

    $score = new Score(['score' => $request->post('score')]);
    $score->game_id = $game->id;
    $score->player_id = $request->post('playerID');

    if ($score->save()) {
      return $this->returnSuccess();
    } else {
      return $this->returnError();
    }
  }
}
