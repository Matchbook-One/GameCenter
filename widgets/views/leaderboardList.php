<?php
/**
 * @var string $title
 * @var array<\fhnw\modules\gamecenter\models\Score> $scores
 * @var ?\fhnw\modules\gamecenter\components\Period $period
 */

use fhnw\modules\gamecenter\widgets\LeaderboardScore;

?>

<?php if (!empty($scores)): ?>
  <div class='panel panel-primary'>
  <!-- Default panel contents -->
  <div class='panel-heading'>
    <h1 class="panel-title"><?= $title ?></h1>
  </div>
  <?php if (!empty($period)): ?>
    <div class='panel-body'>
      <p><span class="start"><?= $period->getStartDate() ?></span> - <span class='end'><?= $period->getEndDate() ?></span></p>
    </div>
  <?php endif ?>
  <div class='table-responsive'>
  <table class='table table-striped'>
  <thead>
  <tr>
    <th scope='col' class="text-right">#</th>
    <th scope='col' class='text-right'>Score</th>
    <th scope='col'>Player</th>
  </tr>
  </thead>
  <tbody class='table-group-divider'>
  <?php foreach ($scores as $key => $score): ?>
    <?= LeaderboardScore::widget(
      [
        'rank'   => $key + 1,
        'score'  => $score->score,
        'player' => $score->player->displayName
      ]
    ) ?>
  <?php endforeach ?>
  </tbody>
  </table>
  </div>
  </div>
<?php endif ?>

