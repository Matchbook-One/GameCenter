<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\assets\GameFilterAssets;
use humhub\libs\Html;
use humhub\widgets\JsWidget;
use JetBrains\PhpStorm\ArrayShape;
use Yii;

class GameFilter extends JsWidget {

  /**
   * @inheritdocs
   */
  public $jsWidget = 'gamecenter.GameFilter';

  /**
   * @inheritdocs
   */
  public $init = true;

  /**
   * @inheritdocs
   * @return string
   */
  public function run(): string {
    GameFilterAssets::register($this->view);

    return Html::dropDownList('', [], ['all' => Yii::t('base', 'All')], $this->getOptions());
  }

  /**
   * getAttributes
   *
   * @return string[]
   */
  #[ArrayShape(['class' => "string", 'style' => "string"])] protected function getAttributes(): array {
    return [
      'class' => 'form-control pull-right visible-md visible-lg',
      'style' => 'width:150px;margin-right:20px',
    ];
  }

  /**
   * getData
   *
   * @return string[]
   */
  #[ArrayShape(['action-change' => "string"])] protected function getData(): array {
    return ['action-change' => 'change'];
  }
}
