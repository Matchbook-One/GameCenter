<?php

/**
 * @var int $rank
 * @var int $score
 * @var string $player
 * @var string $date
 */

use humhub\widgets\TimeAgo;

?>

<tr>
  <td class="text-right"><?= $rank ?></td>
  <td class='text-right'><?= $score ?></td>
  <td><?= $player ?></td>
  <td><?= TimeAgo::widget(['timestamp' => $date]) ?></td>
</tr>
