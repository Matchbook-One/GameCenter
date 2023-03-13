<?php

use fhnw\modules\gamecenter\assets\KeyboardAssets;
use humhub\libs\Html;
use humhub\modules\ui\view\components\View;
use humhub\widgets\Button;

/**
 * @var array<array> $layout
 * @var array $htmlOptions
 * @var string $id
 * @var string $type
 * @var View $this
 */

KeyboardAssets::register($this);

echo Html::beginTag('div', ['class' => 'keyboard']);
foreach ($layout as $row) {
    echo Html::beginTag('div', ['class' => 'keyboard-row']);
    foreach ($row as $val) {
        echo Button::primary($val)
                   ->options($htmlOptions)
                   ->cssClass('keyboard-key')
                   ->loader(false);
    }
    echo Html::endTag('div');
}
echo Html::endTag('div');
