<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use yii\grid\DataColumn;

/**
 * @since   1.0
 * @author  Christian Seiler
 * @package GameCenter
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