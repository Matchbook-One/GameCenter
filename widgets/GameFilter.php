<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\assets\GameFilterAssets;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\libs\Html;
use humhub\widgets\JsWidget;

/**
 * @phpstan-type Attributes array{class: string, style: string}
 */
class GameFilter extends JsWidget
{

    /**
     * @var boolean $init
     * @inheritdocs
     */
    public $init = true;

    /**
     * @var string $jsWidget
     * @inheritdocs
     */
    public $jsWidget = 'gamecenter.GameFilter';

    /**
     * @inheritdocs
     * @return string
     */
    public function run(): string
    {
        GameFilterAssets::register($this->view);

        return Html::dropDownList('', [], ['all' => GameCenterModule::t('base', 'All')], $this->getOptions());
    }

    /**
     * getAttributes
     *
     * @return Attributes
     */
    protected function getAttributes(): array
    {
        return [
            'class' => 'form-control pull-right visible-md visible-lg',
            'style' => 'width:150px; margin-right:20px',
        ];
    }

    /**
     * getData
     *
     * @return string[]
     */
    protected function getData(): array
    {
        return ['action-change' => 'change'];
    }
}
