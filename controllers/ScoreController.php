<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\models\{Game, Score};
use Yii;
use yii\base\Action;
use yii\web\Response;

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
 * @method  getAccessRules(): array
 * @method  getAccess(): ?ControllerAccess
 * @method beforeAction(Action $action): void
 * @method  behaviors(): array
 * @method  renderAjaxContent(string $content): string
 * @method  renderAjaxPartial(string $content): string
 * @method  renderContent(string $content): string
 * @method  forcePostRequest(): bool
 * @method  htmlRedirect(string $url = '')
 * @method  forbidden(): void
 * @method  renderModalClose(): string
 * @method  appendPageTitle(string $title): void
 * @method  prependPageTitle(string $title): void
 * @method  setPageTitle(string $title): void
 * @method  setActionTitles(array $map = [], bool $prependActionTitles = true): void
 * @method  redirect(array|string $url, int $statusCode = 302): Response
 * @method  setJsViewStatus(): void
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
  public function actionCreate(): Response
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
