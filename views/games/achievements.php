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
<h1>Achievements: <?= $game->title ?></h1>
<div>Count: <?= count($achievements) ?></div>

<div class='row'>
  <div class='col-lg-4 col-md-6 col-sm-12'>
    <?php foreach ($achievements as $achievement): ?>
      <?= AchievementCard::with($achievement) ?>
    <?php endforeach ?>
  </div>
</div>