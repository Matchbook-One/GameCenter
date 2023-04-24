<?php

namespace fhnw\modules\gamecenter\components;

use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;

abstract class RestController extends Controller
{
  /** @var string moduleId */
  public static $moduleId = '';

  /**
   * @inheritdoc
   *
   * @param \yii\rest\Action $action
   *
   * @return bool
   * @throws \yii\web\BadRequestHttpException
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
   * @return class-string returns the class name of the active record
   */
  abstract public function getContentActiveRecordClass();

  /**
   * Handles pagination
   *
   * @param ActiveQuery $query
   * @param int         $limit
   *
   * @return \yii\data\Pagination
   */
  protected function handlePagination(ActiveQuery $query, int $limit = 100)
  {
    $limit = (int)Yii::$app->request->get('limit', $limit);
    $page = (int)Yii::$app->request->get('page', 1);

    if ($limit > 100) {
      $limit = 100;
    }

    $page--;

    $countQuery = clone $query;
    $pagination = new Pagination(['totalCount' => $countQuery->count()]);
    $pagination->setPage($page);
    $pagination->setPageSize($limit);

    $query->offset($pagination->offset);
    $query->limit($pagination->limit);

    return $pagination;
  }

  /**
   * Generates error response
   *
   * @param int    $statusCode
   * @param string $message
   * @param array  $additional
   *
   * @return array
   */
  protected function returnError($statusCode = 400, $message = 'Invalid request', $additional = [])
  {
    Yii::$app->response->statusCode = $statusCode;

    return array_merge(['code' => $statusCode, 'message' => $message], $additional);
  }

  /**
   * Generates pagination response
   *
   * @param ActiveQuery $query
   * @param Pagination  $pagination
   * @param array       $data
   *
   * @return array
   */
  protected function returnPagination(ActiveQuery $query, Pagination $pagination, $data)
  {
    return [
      'total'   => $pagination->totalCount,
      'page'    => $pagination->getPage() + 1,
      'pages'   => $pagination->getPageCount(),
      'links'   => $pagination->getLinks(),
      'results' => $data
    ];
  }

  /**
   * Generates success response
   *
   * @param string $message
   * @param int    $statusCode
   * @param array  $additional
   *
   * @return array
   */
  protected function returnSuccess($message = 'Request successful', $statusCode = 200, $additional = [])
  {
    Yii::$app->response->statusCode = $statusCode;

    return array_merge(['code' => $statusCode, 'message' => $message], $additional);
  }
}
