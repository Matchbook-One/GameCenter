<?php

namespace fhnw\modules\gamecenter\assets;

use humhub\components\assets\AssetBundle;

class KeyboardAssets extends AssetBundle
{
    /**
     * @inheritdoc
     * @var string[] $css
     */
    public $css = ['css/keyboard.css'];

    /**
     * @var array{forceCopy: bool} $publishOptions
     * @inheritdoc
     */
    public $publishOptions = ['forceCopy' => true];

    /**
     * @var string $sourcePath
     * @inheritdoc
     */
    public $sourcePath = '@gamecenter/resources';
}
