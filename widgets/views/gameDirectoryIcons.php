<?php
/**
 * @link      https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license   https://www.humhub.com/licences
 */

use humhub\widgets\Link;
use yii\helpers\Html;

/**
 * @var \fhnw\modules\gamecenter\models\Game $game        The Game instance
 * @var int                                  $playerCount the number of payers playing the game
 * @var bool                                 $canViewPlayers
 */

$text = " <span>$playerCount</span>";
$class = 'fa fa-users';
?>
<?php if ($canViewPlayers) : ?>
  <?= Link::withAction($text, 'ui.modal.load', $game->createUrl('/space/membership/members-list'))
          ->cssClass($class) ?>
<?php else: ?>
  <?= Html::tag('span', $text, ['class' => $class]) ?>
<?php endif; ?>
