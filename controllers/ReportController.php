<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use DateTime;
use fhnw\modules\gamecenter\components\RestController;
use fhnw\modules\gamecenter\models\{Game, Play, Report};
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use Yii;
use yii\base\Action;
use yii\web\HttpException;
use yii\web\Response as WebResponse;

/**
 * @package GameCenter/Controllers
 * @method beforeAction(Action $action): bool
 * @method returnError(?int $statusCode = 400, ?string $message = 'Invalid request', ?array $additional = []): \yii\web\Response
 * @method returnSuccess(?string $message = 'Request successful', ?int $statusCode = 200, ?array $additional = []): \yii\web\Response
 */
class ReportController extends RestController
{

  /** @var string the model class name. This property must be set. */
  public $modelClass = Report::class;

  /**
   * Creates an End Report
   *
   * @return WebResponse
   * @throws HttpException
   */
  #[Post(path: '/gamecenter/api/report/end', tags: ['Report'])]
  #[RequestBody(content: new MediaType('application/json', new Schema(ref: '#/components/schemas/ModuleRequestBody')))]
  #[Response(response: 201, description: 'Created')]
  #[Response(response: 400, description: 'Invalid Request')]
  public function actionEnd(): void
  {
    $request = Yii::$app->request;
    $game = Game::findOne(['module' => $request->post('module')])->id;

    $this->report('game_end', (new DateTime())->getTimestamp(), $game);
  }

  /**
   * Creates a Report
   *
   * @return WebResponse
   * @throws HttpException
   */
  #[Post(path: '/gamecenter/report/report', tags: ['Report'])]
  #[RequestBody(content: new MediaType('application/json', new Schema(ref: '#/components/schemas/ReportRequestBody')))]
  #[Response(response: 201, description: 'Created')]
  #[Response(response: 400, description: 'Invalid Request')]
  public function actionReport(): void
  {
    $request = Yii::$app->request;
    $moduleId = $request->post('module');
    $game = $this->getGameId($moduleId);
    $option = $request->post('option');
    $value = $request->post('value');

    $this->report($option, $value, $game);
  }

  /**
   * Creates a Start Report
   *
   * @return WebResponse
   * @throws HttpException
   */
  #[Post(path: '/gamecenter/report/start', tags: ['Report'])]
  #[RequestBody(content: new MediaType('application/json', new Schema(ref: '#/components/schemas/ModuleRequestBody')))]
  #[Response(response: 201, description: 'Created')]
  #[Response(response: 400, description: 'Invalid Request')]
  public function actionStart(): void
  {
    $request = Yii::$app->request;
    $moduleId = $request->post('module');
    $game = $this->getGameId($moduleId);

    $this->savePlay($game);

    $this->report('game_start', (new DateTime())->getTimestamp(), $game);
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
   * @param int    $game
   *
   * @return void
   * @throws HttpException
   */
  private function report(string $option, string $value, int $game): void
  {
    $report = new Report();
    $report->game_id = $game;
    $report->player_id = $this->getPlayerID();
    $report->option = $option;
    $report->value = $value;

    if (!$report->save()) {
      throw new HttpException(
          500,
          $report->getErrorMessage()
      );
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
