<?php

use fhnw\modules\gamecenter\helpers\Url;
use fhnw\modules\gamecenter\widgets\AchievementCard;
use humhub\widgets\Button;

/**
 * @var \fhnw\modules\gamecenter\models\PlayerAchievement $achievements
 * @var \fhnw\modules\gamecenter\models\Game $game
 */

?>
<?= Button::back(Url::toGamesOverview()) ?>
<h1>Achievements: <strong><?= $game->title ?></strong></h1>
<div>Count: <?= count($achievements) ?></div>

<div class='row'>

  <?php foreach ($achievements as $achievement): ?>
    <?= AchievementCard::with($achievement) ?>
  <?php endforeach ?>
</div>