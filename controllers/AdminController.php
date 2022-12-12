<?php

namespace humhub\modules\fhnw\gamecenter\controllers;

use humhub\modules\admin\components\Controller;

class AdminController extends Controller  {

    /**
     * Render admin only page
     *
     * @return string
     */
    public function actionIndex(): string {
        return $this->render('index');
    }

}

