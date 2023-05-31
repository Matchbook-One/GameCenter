<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\models\Game;
use yii\bootstrap\Html;

/**
 * @var Game   $game
 * @var string $acronym
 * @var bool   $link
 * @var array  $linkOptions
 * @var array  $acronymHtmlOptions
 * @var array  $imageHtmlOptions
 */

if ($link) :
  echo Html::beginTag('a', $linkOptions);
endif;
echo Html::beginTag('div', $acronymHtmlOptions);
echo $acronym;
echo Html::endTag('div');
echo Html::img($game->getGameImage(), $imageHtmlOptions);
if ($link) :
  echo Html::endTag('a');
endif;
