<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\models\GameSearch;
use fhnw\modules\gamecenter\permissions\ManageGameCenter;
use humhub\modules\admin\components\Controller;
use Yii;

/**
 * Admin Controller
 */
class AdminController extends Controller {

  /**
   * @return string
   */
  public function actionAchievements(): string {
    return $this->render('achievements');
  }

  /**
   * Render admin only page
   *
   * @return string
   */
  public function actionIndex(): string {
    $searchModel = new GameSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render(
      'index',
      compact('dataProvider', 'searchModel')
    );
  }

  /**
   * @inheritdoc
   *
   * @return array
   *
   * @noinspection PhpOverridingMethodVisibilityInspection
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function getAccessRules(): array {
    return [
      ['permissions' => [ManageGameCenter::class]],
    ];
  }

  /**
   * @inheritdoc
   * @return void
   */
  public function init(): void {
    $this->subLayout = '@gamecenter/views/layouts/gamecenter';
    $this->appendPageTitle(Yii::t('GamecenterModule.base', 'GameCenter'));
    parent::init();
  }
}