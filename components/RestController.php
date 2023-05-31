<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use humhub\components\Controller;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\rest\{Serializer};
use yii\web\{BadRequestHttpException, JsonParser, Response};

use function array_merge;

/**
 * Class RestController
 *
 * @package GameCenter/Controllers
 */
class RestController extends Controller
{

  public string $collection = '';

  /**
   * @var bool enableCsrfValidation
   * @inheritdoc
   */
  public $enableCsrfValidation = false;

  private ?int $playerID;

  /**
   * @throws InvalidConfigException
   */
  public function init(): void
  {
    parent::init();
    Yii::$app->response->format = 'json';
    $this->playerID = Yii::$app->user->id;

    $this->serializer = [
        'class'              => Serializer::class,
        'collectionEnvelope' => $this->collection
    ];
  }

  /**
   * @inheritdoc
   *
   * @param Action $action
   *
   * @return bool
   * @see \yii\web\Controller::beforeAction()
   * @throws BadRequestHttpException
   */
  public function beforeAction($action): bool
  {
    Yii::$app->response->format = 'json';
    Yii::$app->request->parsers['application/json'] = JsonParser::class;

    return parent::beforeAction($action);
  }

  /**
   * @return ?int
   */
  public function getPlayerID(): ?int
  {
    return $this->playerID;
  }

  /**
   * Generates error response
   *
   * @param int    $statusCode
   * @param string $message
   * @param array  $additional
   *
   * @deprecated
   * @return Response
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
   * @param int    $statusCode
   * @param array  $additional
   *
   * @return Response
   */
  protected function returnSuccess(string $message = 'Request successful', int $statusCode = 200, array $additional = []): Response
  {
    $response = Yii::$app->getResponse();
    $response->format = Response::FORMAT_JSON;
    $response->statusCode = $statusCode;
    $response->data = array_merge(['message' => $message], $additional);

    return $response;
  }

}
