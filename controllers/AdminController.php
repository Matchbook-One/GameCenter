<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\GameSearch;
use humhub\modules\admin\components\Controller;
use Yii;

/**
 * Admin Controller
 */
class AdminController extends Controller
{
    /**
     * @inheritdoc
     * @return     void
     */
    public function init(): void
    {
        $this->subLayout = '@gamecenter/views/layouts/gamecenter';
        $this->appendPageTitle(GameCenterModule::t('config', 'GameCenter'));
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
        $searchModel  = new GameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('dataProvider', 'searchModel'));
    }
}
