<?php

namespace fhnw\modules\gamecenter\controllers;

use humhub\components\Controller;
use OpenApi\Attributes\PathItem;
use Yii;
use yii\swagger\OpenAPIRenderer;
use yii\swagger\SwaggerUIRenderer;

#[PathItem(path: '/gamecenter/docs/api')]
class DocsController extends Controller
{

  // public $subLayout = '@gamecenter/views/layouts/gamecenter';

  public function actions(): array
  {
    $actions = parent::actions();

    $actions['index'] = [
      'class'   => SwaggerUIRenderer::class,
      'restUrl' => "/gamecenter/docs/api",
    ];

    $actions['api'] = [
      'class'       => OpenAPIRenderer::class,
      // Тhe list of directories that contains the swagger annotations.
      'scanDir'     => [
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

  public function beforeAction($action): bool
  {
    Yii::$app->request->parsers['application/json'] = 'yii\web\JsonParser';

    return parent::beforeAction($action);
  }

}