<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\components\ContentController;
use fhnw\modules\gamecenter\components\RestController;
use OpenApi\Attributes\Contact;
use OpenApi\Attributes\Info;
use OpenApi\Attributes\OpenApi;
use OpenApi\Attributes\PathItem;
use Yii;
use yii\base\Action;
use yii\swagger\OpenAPIRenderer;
use yii\swagger\SwaggerUIRenderer;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

#[OpenApi]
#[Info(version: '1.0.0', title: 'GameCenter API')]
#[Contact(name: 'Christian Seiler', email: 'christian@christianseiler.ch')]
#[PathItem(path: '/gamecenter/api')]
class DocsController extends Controller
{

  public function actions(): array
  {
    $actions = parent::actions();
    $actions['index'] = [
        'class'   => SwaggerUIRenderer::class,
        'restUrl' => '/gamecenter/api',
    ];

    $actions['api'] = [
        'class'       => OpenAPIRenderer::class,
      // Тhe list of directories that contains the swagger annotations.
        'scanDir'     => [
            Yii::getAlias('@gamecenter/components'),
            Yii::getAlias('@gamecenter/controllers'),
            Yii::getAlias('@gamecenter/models'),
            Yii::getAlias('@gamecenter/openapi')
        ],
        'scanOptions' => [
            'exclude' => [
                AdminController::class,
                ContentController::class,
                DocsController::class,
                GamesController::class,
                RestController::class
            ]
        ]
    ];

    $actions['error'] = ['class' => 'yii\web\ErrorAction'];

    return $actions;
  }

  /**
   * @param Action $action
   *
   * @throws BadRequestHttpException
   */
  public function beforeAction($action): bool
  {
    Yii::$app->request->parsers['application/json'] = 'yii\web\JsonParser';

    return parent::beforeAction($action);
  }

}
