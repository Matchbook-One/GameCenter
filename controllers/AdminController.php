<?php
declare(strict_types=1);

namespace humhub\modules\gamecenter\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\gamecenter\models\GameSearch;
use Yii;

/**
 *
 */
class AdminController extends Controller {

  /**
   * @inheritdoc
   */
  public function init() {

    $this->subLayout = '@gamecenter/views/layouts/gamecenter';
    parent::init();
  }

  /**
   * Render admin only page
   *
   * @return string
   */
  public function actionIndex(): string {
    $searchModel = new GameSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
      'searchModel'  => $searchModel
    ]);
  }

}

