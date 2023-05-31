<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\GameSearch;
use humhub\components\Controller;
use humhub\modules\ui\view\components\View;
use Yii;

/**
 * Admin Controller
 *
 * @package GameCenter/Controllers
 * @property ?string      $subLayout
 * @property string       $pageTitle
 * @property array        $actionTitlesMap
 * @property bool         $prependActionTitles
 * @property class-string $access
 * @property View         $view
 */
class AdminController extends Controller
{

  /** @inheritdoc */
  public $subLayout = '@gamecenter/views/admin/layout';

  /**
   * @inheritdoc
   * @return     void
   */
  public function init(): void
  {
    $this->appendPageTitle(GameCenterModule::t('base', 'GameCenter'));

    parent::init();
  }

  /**
   * @return string
   */
  public function actionAchievements(): string
  {
    return $this->render('achievements');
  }

  /**
   * Render admin only page
   *
   * @return string
   */
  public function actionIndex(): string
  {
    $searchModel = new GameSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', compact('dataProvider', 'searchModel'));
  }

}
