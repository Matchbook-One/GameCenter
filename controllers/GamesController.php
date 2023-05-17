<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\components\GameDirectoryQuery;
use fhnw\modules\gamecenter\widgets\GameDirectoryCard;
use humhub\components\Controller;
use Yii;
use yii\helpers\Url;

/**
 * IndexController
 *
 * @package GameCenter/Controllers
 */
class GamesController extends Controller
{
  /** @var string $subLayout */
  public $subLayout = '@gamecenter/views/layouts/gamecenter';

  /**
   * @inheritdoc
   * @return void
   * @throws \yii\web\HttpException
   */
  public function init(): void
  {
    //$this->setActionTitles(['gamecenter' => GameCenterModule::t('base', 'Games'),]);
    parent::init();
  }

  /** @return string */
  public function actionIndex(): string
  {
    $gameDirectoryQuery = new GameDirectoryQuery();

    $urlParams = Yii::$app->request->getQueryParams();
    unset($urlParams['page']);
    array_unshift($urlParams, '/gamecenter/gamecenter/load-more');
    /** @var \humhub\modules\ui\view\components\View $view */
    $view = $this->getView();
    $view->registerJsConfig('cards', ['loadMoreUrl' => Url::to($urlParams)]);

    return $this->render('index', ['games' => $gameDirectoryQuery]);
  }

  /**
   * Action to load cards for next page by AJAX
   *
   * @throws \Exception
   */
  public function actionLoadMore(): string
  {
    $gameQuery = new GameDirectoryQuery();

    $gameCards = '';
    foreach ($gameQuery->all() as $game) {
      $gameCards .= GameDirectoryCard::widget(['game' => $game]);
    }

    return $gameCards;
  }
}
