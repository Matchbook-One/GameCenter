<?php

use fhnw\modules\gamecenter\components\Period;
use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\Score;
use fhnw\modules\gamecenter\widgets\LeaderboardScore;

/**
 * @var string       $title
 * @var array<Score> $scores
 * @var ?Period      $period
 */

?>

<?php if (!empty($scores)): ?>
  <div class='panel panel-primary'>
    <!-- Default panel contents -->
    <div class='panel-heading'>
      <h1 class="panel-title"><?= $title ?></h1>
    </div>
    <div class='panel-body'>
      <?php if (!empty($period)): ?>
        <p>
          <span class="start"><?= $period->getStartDate() ?></span> - <span class='end'><?= $period->getEndDate() ?></span>
        </p>
      <?php endif ?>
    </div>
    <div class='table-responsive'>
      <table class='table table-striped'>
        <thead>
        <tr>
          <th scope='col' class="text-right">#</th>
          <th scope='col' class='text-right'><?= GameCenterModule::t('base', 'Score') ?></th>
          <th scope='col'><?= GameCenterModule::t('base', 'Player') ?></th>
          <th scope='col'><?= GameCenterModule::t('base', 'Date') ?></th>
        </tr>
        </thead>
        <tbody class='table-group-divider'>
        <?php foreach ($scores as $key => $score): ?>
          <?= LeaderboardScore::widget(
              [
                  'rank'   => $key + 1,
                  'score'  => $score->score,
                  'player' => $score->player->displayName,
                  'date'   => $score->timestamp
              ]
          ) ?>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif;
