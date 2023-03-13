<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use Exception;
use fhnw\modules\gamecenter\widgets\Image as GameImage;

/**
 * GameImageColumn
 *
 * @package GameCenter
 * @since   1.0.0
 * @author  Christian Seiler <christian@christianseiler.ch>
 */
class GameImageColumn extends GameBaseColumn
{
    /**
     * @inerhitdoc
     * @return void
     */
    public function init(): void
    {
        parent::init();

        $this->options['style'] = 'width:38px';
    }

    /**
     * @inheritdoc
     *
     * @param     $model
     * @param     $key
     * @param int $index
     *
     * @return string
     * @throws Exception
     */
    protected function renderDataCellContent($model, $key, $index): string
    {
        return GameImage::widget(['game' => $this->getGame($model), 'width' => 34, 'link' => true]);
    }
}
