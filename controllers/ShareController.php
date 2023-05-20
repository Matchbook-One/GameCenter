<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\models\Game;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\post\models\Post;
use humhub\modules\user\models\User;
use OpenApi\Attributes\{MediaType, Post as OAPost, RequestBody, Response, Schema};
use Yii;
use yii\base\Action;
use yii\base\Exception;
use yii\web\Response as WebResponse;

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
class ShareController extends RestController
{

  /**
   * Share
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[OAPost(path: '/gamecenter/share', tags: ['Share'])]
  #[RequestBody(content: new MediaType('application/json', new Schema('#/components/schemas/ShareRequestBody')))]
  #[Response(response: 201, description: 'OK')]
  #[Response(response: 400, description: 'Invalid request')]
  public function actionIndex(): WebResponse
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;

    $game = Game::findOne(['module' => $request->post('module')]);
    $contentContainer = ContentContainer::findOne(['class' => User::class, 'pk' => $this->getPlayerID()]);
    try {
      $post = new Post($contentContainer);
      $post->message = $request->post('message');
      $post->save();

      return $this->returnSuccess(statusCode: 201);
    }
    catch (Exception $e) {
      return $this->returnError();
    }
  }

}
