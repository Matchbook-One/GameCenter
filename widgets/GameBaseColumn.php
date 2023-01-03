<?php
declare(strict_types=1);

namespace humhub\modules\gamecenter\widgets;

use humhub\modules\gamecenter\models\Game;
use yii\grid\DataColumn;

/**
 * @author Christian Seiler
 * @module gamecenter
 * @since  1.0
 */
class GameBaseColumn extends DataColumn {

  /**
   * @var string|null name of space model attribute
   */
  public ?string $gameAttribute = null;

  /**
   * Returns the space record
   *
   * @param $record
   *
   * @return Game the game model
   */
  public function getGame($record): Game {
    if ($this->gameAttribute === null) {
      return $record;
    }

    $attributeName = $this->gameAttribute;
    return $record->$attributeName;
  }
}