<?php
/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 */

namespace fhnw\modules\gamecenter\controllers;

use Yii;
use yii\web\JsonParser;

use function array_merge;

/**
 * Class ScoreController
 *
 * @package GameCenter
 * @since   1.0.0
 */
class RestController extends \humhub\components\Controller
{

  /**
   * @var bool adminOnly
   */
  public $adminOnly = false;
  /**
   * @var bool enableCsrfValidation
   * @inheritdoc
   */
  public $enableCsrfValidation = false;

  /**
   * @inheritdoc
   *
   * @param \yii\base\Action $action
   *
   * @return bool
   * @see \yii\web\Controller::beforeAction()
   */
  public function beforeAction($action)
  {
    Yii::$app->response->format = 'json';

    Yii::$app->request->setBodyParams([]);
    Yii::$app->request->enableCsrfCookie = false;
    Yii::$app->request->parsers['application/json'] = JsonParser::class;

    return parent::beforeAction($action);
  }

  /**
   * Generates error response
   *
   * @param int    $statusCode
   * @param string $message
   * @param array  $additional
   *
   * @return \yii\web\Response
   */
  protected function returnError($statusCode = 400, $message = 'Invalid request', $additional = [])
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
   * @return \yii\web\Response
   */
  protected function returnSuccess($message = 'Request successful', $statusCode = 200, $additional = [])
  {
    $response = Yii::$app->getResponse();
    $response->statusCode = $statusCode;
    $response->data = array_merge(['message' => $message], $additional);

    return $response;
  }

}
