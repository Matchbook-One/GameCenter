<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use yii\grid\DataColumn;

/**
 *
 */
class GameBaseColumn extends DataColumn
{

    /**
     * @var ?string name of space model attribute
     */
    public ?string $gameAttribute = null;

    /**
     * Returns the space record
     *
     * @param $record ??
     *
     * @return Game the game model
     */
    public function getGame($record): Game
    {
        if ($this->gameAttribute === null) {
            return $record;
        }

        $attributeName = $this->gameAttribute;

        return $record->$attributeName;
    }
}
