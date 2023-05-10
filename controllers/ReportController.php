<?php

namespace fhnw\modules\gamecenter\controllers;

use DateTime;
use fhnw\modules\gamecenter\models\{Game, Play, Player, Report};
use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * @package GameCenter/Controllers
 * @method beforeAction(Action $action): bool
 * @method returnError(?int $statusCode = 400, ?string $message = 'Invalid request', ?array $additional = []): \yii\web\Response
 * @method returnSuccess(?string $message = 'Request successful', ?int $statusCode = 200, ?array $additional = []): \yii\web\Response
 */
class ReportController extends RestController
{
  private Player $player;

  public function init()
  {
    parent::init(); // TODO: Change the autogenerated stub
    $this->player = Yii::$app->user;
  }

  /**
   * Creates a Start Report
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  public function actionEnd(): Response
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;
    $game = Game::findOne(['module' => $request->post('module')])->id;

    return $this->report('game_end', (new DateTime())->getTimestamp(), $game);
  }

  /**
   * Creates a Start Report
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  public function actionReport(): Response
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;
    $moduleId = $request->post('module');
    $game = $this->getGameId($moduleId);
    $option = $request->post('option');
    $value = $request->post('value');

    return $this->report($option, $value, $game);
  }

  /**
   * Creates a Start Report
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  public function actionStart(): Response
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;
    $moduleId = $request->post('module');
    $game = $this->getGameId($moduleId);

    $this->savePlay($game);

    return $this->report('game_start', (new DateTime())->getTimestamp(), $game);
  }

  /**
   * @param string $moduleId
   *
   * @return int
   */
  private function getGameId(string $moduleId): int
  {
    return Game::findOne(['module' => $moduleId])->id;
  }

  /**
   * Creates a Report
   *
   * @param string $option
   * @param string $value
   * @param int $game
   *
   * @return \yii\web\Response
   */
  private function report(string $option, string $value, int $game): Response
  {
    $report = new Report();
    $report->game_id = $game;
    $report->player_id = $this->player->id;
    $report->option = $option;
    $report->value = $value;

    if ($report->save()) {
      return $this->returnSuccess();
    } else {
      return $this->returnError();
    }
  }

  /**
   * @param $game
   *
   * @return void
   */
  private function savePlay($game)
  {
    $play = Play::findOne(['game_id' => $game, 'player_id' => $this->player->id]);
    if (!$play) {
      $play = new Play(['game_id' => $game, 'player_id' => $this->player->id]);
    }
    $play->last_played = (new DateTime())->getTimestamp();

    $play->save();
  }
}