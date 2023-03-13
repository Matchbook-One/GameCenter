<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\assets\Assets;
use fhnw\modules\gamecenter\GameCenterModule;
use humhub\widgets\Button;

// Register our module assets, this could also be done within the controller
Assets::register($this);

$displayName = (Yii::$app->user->isGuest) ? GameCenterModule::t('base', 'Guest') : Yii::$app->user->getIdentity()->displayName;

// Add some configuration to our js module
$this->registerJsConfig(
    'gamecenter', [
                    'username' => (Yii::$app->user->isGuest) ? $displayName : Yii::$app->user->getIdentity()->username,
                    'text'     => [
                        'hello' => GameCenterModule::t('base', 'Hi there {name}!', ['name' => $displayName]),
                    ],
                ]
)

?>

<div class="panel-heading">
    <strong>GameCenter</strong>
    <?= GameCenterModule::t('base', 'Overview') ?>
</div>

<div class="panel-body">
    <p><?= GameCenterModule::t('base', 'Hello World!') ?></p>

    <?= Button::primary(GameCenterModule::t('base', 'Say Hello!'))
              ->action('gamecenter.hello')
              ->loader(false); ?>
</div>
