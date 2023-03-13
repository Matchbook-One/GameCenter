<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use humhub\components\ActiveRecord;

/**
 * GameTitleColumn
 *
 * @package GameCenter
 * @since   1.0.0
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @property ?string $attribute
 * @property ?string $label
 */
class GameTitleColumn extends GameBaseColumn
{
    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            $this->attribute = 'title';
        }
    }

    /**
     * @inheritdoc
     *
     * @param ActiveRecord $model the data model
     * @param string       $key   the key associated with the data model
     * @param int          $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     *
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
        $game = $this->getGame($model);

        $badge = '';

        return "<div>$game->title<br /><small>{Helpers::trimText($game->description, 100)}</small></div>";
    }
}
