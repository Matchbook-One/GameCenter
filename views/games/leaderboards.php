<?php
/**
 * @var \humhub\modules\ui\view\components\View            $view
 * @var \fhnw\modules\gamecenter\models\Game               $game
 * @var array<\fhnw\modules\gamecenter\models\Leaderboard> $leaderboards
 */

use fhnw\modules\gamecenter\helpers\Url;
use fhnw\modules\gamecenter\widgets\LeaderboardCard;
use humhub\widgets\Button;

?>
<?= Button::back(Url::toGamesOverview()) ?>
<h1>Game: <strong><?= $game->title ?></strong></h1>
<div class="row">
  <?php foreach ($leaderboards as $leaderboard): ?>
    <div class=" col-lg-6 col-sm-12"><?= LeaderboardCard::withBoard($leaderboard) ?></div>
  <?php endforeach ?>
</div>
