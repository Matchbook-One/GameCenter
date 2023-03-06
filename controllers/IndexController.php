<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\controllers;

use humhub\components\Controller;

/**
 * IndexController
 *
 * @author     Christian Seiler
 * @version    1.0
 * @package    GameCanter
 */
class IndexController extends Controller {

    public $subLayout = "@gamecenter/views/layouts/default";

    /**
     * Renders the index view for the module
     *
     * @return string
     */
  public function actionIndex(): string {
        return $this->render('index');
    }

}
