<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\widgets;

use Exception;
use fhnw\modules\gamecenter\widgets\Image as GameImage;

/**
 * GameImageColumn
 *
 * @since 1.0
 */
class GameImageColumn extends GameBaseColumn {

  /**
   * @inerhitdoc
   */
  public function init() {
    parent::init();

    $this->options['style'] = 'width:38px';
  }

  /**
   * @inheritdoc
   * @throws Exception
   */
  protected function renderDataCellContent($model, $key, $index): string {
    return GameImage::widget(['game' => $this->getGame($model), 'width' => 34, 'link' => true]);
  }
}