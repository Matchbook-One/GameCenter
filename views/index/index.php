<?php

use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
\humhub\modules\fhnw\gamecenter\assets\Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? Yii::t('GamecenterModule.base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig("gamecenter", [
    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
    'text' => [
        'hello' => Yii::t('GamecenterModule.base', 'Hi there {name}!', ["name" => $displayName])
    ]
])

?>

<div class="panel-heading"><strong>Gamecenter</strong> <?= Yii::t('GamecenterModule.base', 'overview') ?></div>

<div class="panel-body">
    <p><?= Yii::t('GamecenterModule.base', 'Hello World!') ?></p>

    <?=  Button::primary(Yii::t('GamecenterModule.base', 'Say Hello!'))->action("gamecenter.hello")->loader(false); ?></div>
