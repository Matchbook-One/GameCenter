<?php

/**
 * @author       Christian Seiler <christian@christianseiler.ch>
 * @since        1.0.0
 * @noinspection PhpUnused
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\components\actions\HighscoreAction;
use fhnw\modules\gamecenter\components\actions\ScoreCreateAction;
use fhnw\modules\gamecenter\components\actions\ScoreViewAction;
use fhnw\modules\gamecenter\components\RestController;
use fhnw\modules\gamecenter\models\{Score, ScoreDto};
use OpenApi\Attributes\{Get, JsonContent, PathParameter, Post, RequestBody, Response};
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;

/**
 * Class ScoreController
 *
 * @package GameCenter/Controllers
 */
#[Get(path: '/gamecenter/api/{module}/highscore', tags: ['Score'])]
#[PathParameter('module', name: 'module')]
#[Response(response: 200, description: 'OK')]
#[Response(response: 400, description: 'Invalid request')]
#[Post(path: '/gamecenter/api/{module}/score', tags: ['Score'])]
#[PathParameter('module', name: 'module')]
#[RequestBody(content: new JsonContent(ref: ScoreDto::class))]
#[Response(response: 200, description: 'OK', content: new JsonContent(ref: Score::class))]
#[Response(response: 400, description: 'Invalid request')]
class ScoreController extends RestController
{

  public string $collection = 'scores';

  /** @var string the model class name. This property must be set. */
  public $modelClass = Score::class;

  /**
   * {@inheritdoc}
   */
  public function actions(): array
  {
    $actions = parent::actions();
    $path = StringHelper::explode($this->request->pathInfo, '/');
    Yii::warning(Json::encode($path));
    if (ArrayHelper::isIn('highscore', $path)) {
      $actions['view'] = [
          'class'    => HighscoreAction::class,
          'playerID' => $this->getPlayerID() || 1
      ];
    }
    elseif (ArrayHelper::isIn('score', $path)) {
      $actions['view'] = ['class' => ScoreViewAction::class];
      $actions['create'] = [
          'class' => ScoreCreateAction::class
      ];
    }

    return $actions;
  }

}
