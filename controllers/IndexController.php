<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use humhub\components\Controller;

/**
 * IndexController
 *
 * @package GameCenter
 * @version 1.0
 * @author  Christian Seiler <christian@christianseiler.ch>
 */
class IndexController extends Controller
{

  /**
   * @var string $subLayout the name of the sub layout to be applied to this controller's views.
   *                        This property mainly affects the behavior of [[render()]].
   */
  public $subLayout = '@admin/views/layouts/main';

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
