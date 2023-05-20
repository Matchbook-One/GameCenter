<?php

namespace fhnw\modules\gamecenter\controllers;

use DateTime;
use fhnw\modules\gamecenter\models\{Game, Play, Report};
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
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

  /**
   * Creates an End Report
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[Post(path: '/gamecenter/report/end', tags: ['Report'])]
  #[RequestBody(content: new MediaType('application/json', new Schema(ref: '#/components/schemas/ModuleRequestBody')))]
  #[OAResponse(response: 201, description: 'Created')]
  #[OAResponse(response: 400, description: 'Invalid Request')]
  public function actionEnd(): Response
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;
    $game = Game::findOne(['module' => $request->post('module')])->id;

    return $this->report('game_end', (new DateTime())->getTimestamp(), $game);
  }

  /**
   * Creates a Report
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[Post(path: '/gamecenter/report/report', tags: ['Report'])]
  #[RequestBody(content: new MediaType('application/json', new Schema(ref: '#/components/schemas/ReportRequestBody')))]
  #[OAResponse(response: 201, description: 'Created')]
  #[OAResponse(response: 400, description: 'Invalid Request')]
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
  #[Post(path: '/gamecenter/report/start', tags: ['Report'])]
  #[RequestBody(content: new MediaType('application/json', new Schema(ref: '#/components/schemas/ModuleRequestBody')))]
  #[OAResponse(response: 201, description: 'Created')]
  #[OAResponse(response: 400, description: 'Invalid Request')]
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
    $report->player_id = $this->getPlayerID();
    $report->option = $option;
    $report->value = $value;

    if ($report->save()) {
      return $this->returnSuccess();
    }
    else {
      return $this->returnError();
    }
  }

  /**
   * @param $game
   *
   * @return void
   */
  private function savePlay($game): void
  {
    $play = Play::findOne(['game_id' => $game, 'player_id' => $this->getPlayerID()]);
    if (!$play) {
      $play = new Play(['game_id' => $game, 'player_id' => $this->getPlayerID()]);
    }
    $play->last_played = (new DateTime())->getTimestamp();

    $play->save();
  }

}
