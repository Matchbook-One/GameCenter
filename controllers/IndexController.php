<?php

namespace humhub\modules\fhnw\gamecenter\controllers;

use humhub\components\Controller;

class IndexController extends Controller {

    public $subLayout = "@gamecenter/views/layouts/default";

    /**
     * Renders the index view for the module
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

}

