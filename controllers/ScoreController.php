<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\models\{Game, Score};
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use Yii;
use yii\base\Action;
use yii\web\Response as WebResponse;

use const SORT_DESC;

/**
 * Class ScoreController
 *
 * @package GameCenter/Controllers
 * @property ?string $subLayout
 * @property string $pageTitle
 * @property array $actionTitlesMap
 * @property bool $prependActionTitles
 * @property class-string $access
 * @property array<string> $doNotInterceptActionIds
 * @property \humhub\components\View $view
 * @method init(): void
 * @method getAccessRules(): array
 * @method getAccess(): ?ControllerAccess
 * @method beforeAction(Action $action): void
 * @method behaviors(): array
 * @method renderAjaxContent(string $content): string
 * @method renderAjaxPartial(string $content): string
 * @method renderContent(string $content): string
 * @method forcePostRequest(): bool
 * @method htmlRedirect(string $url = '')
 * @method forbidden(): void
 * @method renderModalClose(): string
 * @method appendPageTitle(string $title): void
 * @method prependPageTitle(string $title): void
 * @method setPageTitle(string $title): void
 * @method setActionTitles(array $map = [], bool $prependActionTitles = true): void
 * @method redirect(array|string $url, int $statusCode = 302): Response
 * @method setJsViewStatus(): void
 * @method isNotInterceptedAction(string $actionId = null): bool
 */
class ScoreController extends RestController
{

  /**
   * Creates a score
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[Post(path: "/gamecenter/score/create", tags: ['Score'])]
  #[RequestBody(content: new MediaType('application/json', new Schema('#/components/schemas/ScoreRequestBody')))]
  #[Response(response: 200, description: "OK")]
  #[Response(response: 400, description: "Invalid request")]
  public function actionCreate(): WebResponse
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;

    $game = Game::findOne(['module' => $request->post('module')]);

    $score = new Score(['score' => $request->post('score')]);
    $score->game_id = $game->id;
    $score->player_id = $this->getPlayerID();

    if ($score->save()) {
      return $this->returnSuccess();
    }
    else {
      return $this->returnError();
    }
  }

  /**
   * Creates a score
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[Post(path: '/gamecenter/score/highscore', tags: ['Score'])]
  #[RequestBody(content: new MediaType('application/json', new Schema('#/components/schemas/ModuleRequestBody')))]
  #[Response(response: 200, description: 'OK')]
  #[Response(response: 400, description: 'Invalid request')]
  public function actionHighscore(): WebResponse
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;

    $game = Game::findOne(['module' => $request->post('module')]);

    /** @var ?Score $score */
    $score = Score::find()
                  ->where(['game_id' => $game->id])
                  ->andWhere(['player_id' => $this->getPlayerID()])
                  ->orderBy(['score' => SORT_DESC])
                  ->one();

    if ($score) {
      return $this->returnSuccess(additional: ['score' => $score]);
    }
    else {
      return $this->returnError();
    }
  }

}
