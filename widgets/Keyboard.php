<?php

namespace fhnw\modules\gamecenter\widgets;

use humhub\components\Widget;
use Throwable;

/**
 * @package gamecenter\widgets
 */
class Keyboard extends Widget
{

    private const NUMMERIC = 'nummeric';

    private const QWERTZ = 'qwertz';

    /** @var array<string,string> */
    public $htmlOptions = [];
    /** @var array */
    public $layout;
    /** @var string */
    public $type;

    /**
     * @return self
     * @static
     */
    public static function nummeric()
    {
        $layout = [
            [7, 8, 9],
            [4, 5, 6],
            [1, 2, 3],
            [0]
        ];

        return new static(['type' => self::NUMMERIC, 'layout' => $layout]);
    }

    public static function qwertz()
    {
        $layout = [
            ['q', 'w', 'e', 'r', 't', 'z', 'u', 'i', 'o', 'p', 'ü'],
            ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'ö', 'ä'],
            ['y', 'x', 'c', 'v', 'b', 'n', 'm', 'DEL', '↩︎']
        ];

        return new static(['type' => self::QWERTZ, 'layout' => $layout]);
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function __toString()
    {
        return $this::widget($this->getWidgetOptions());
    }

    /**
     * @param callable $handler
     *
     * @return Keyboard
     */
    public function action($handler)
    {
        $this->htmlOptions['data-action-click'] = $handler;

        return $this;
    }

    /**
     * @return array
     */
    public function getWidgetOptions()
    {
        return [
            'id'          => $this->id,
            'type'        => $this->type,
            'layout'      => $this->layout,
            'htmlOptions' => $this->htmlOptions
        ];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->getId(false)) {
            $this->htmlOptions['id'] = $this->getId(false);
        }

        return $this->render('@gamecenter/widgets/views/keyboard', $this->getWidgetOptions());
    }

}
