<?php
/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use humhub\components\Controller;
use Yii;
use yii\web\JsonParser;
use yii\web\Response;

use function array_merge;

/**
 * Class RestController
 *
 * @package GameCenter/Controllers
 * @property ?string $subLayout
 * @property string $pageTitle
 * @property array $actionTitlesMap
 * @property bool $prependActionTitles
 * @property class-string $access
 * @property array<string> $doNotInterceptActionIds
 * @property \humhub\components\View $view
 * @method  getAccessRules(): array
 * @method  getAccess(): ?ControllerAccess
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
class RestController extends Controller
{

  /**
   * @var bool adminOnly
   */
  public bool $adminOnly = false;
  /**
   * @var bool enableCsrfValidation
   * @inheritdoc
   */
  public $enableCsrfValidation = false;
  private int $playerID;

  public function init(): void
  {
    parent::init();
    Yii::$app->response->format = 'json';
    $this->playerID = Yii::$app->user->id;
  }

  /**
   * @inheritdoc
   *
   * @param \yii\base\Action $action
   *
   * @return bool
   * @see \yii\web\Controller::beforeAction()
   */
  public function beforeAction($action): bool
  {
    Yii::$app->response->format = 'json';

    Yii::$app->request->setBodyParams(null);
    Yii::$app->request->enableCsrfCookie = false;
    Yii::$app->request->parsers['application/json'] = JsonParser::class;

    return parent::beforeAction($action);
  }

  public function getPlayerID(): int
  {
    return $this->playerID;
  }

  /**
   * Generates error response
   *
   * @param int $statusCode
   * @param string $message
   * @param array $additional
   *
   * @return \yii\web\Response
   */
  protected function returnError(int $statusCode = 400, string $message = 'Invalid request', array $additional = []): Response
  {
    $response = Yii::$app->getResponse();
    $response->statusCode = $statusCode;
    $response->data = array_merge(['message' => $message], $additional);

    return $response;
  }

  /**
   * Generates success response
   *
   * @param string $message
   * @param int $statusCode
   * @param array $additional
   *
   * @return \yii\web\Response
   */
  protected function returnSuccess(string $message = 'Request successful', int $statusCode = 200, array $additional = []): Response
  {
    $response = Yii::$app->getResponse();
    $response->statusCode = $statusCode;
    $response->data = array_merge(['message' => $message], $additional);

    return $response;
  }

}
