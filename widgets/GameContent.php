<?php

namespace fhnw\modules\gamecenter\widgets;

use humhub\components\Widget;
use humhub\modules\content\components\ContentContainerActiveRecord;

/**
 * @package GameCenter/Widgets
 */
class GameContent extends Widget
{
  /**
   * @var string
   */
  public string $content = '';

  /**
   * @var ContentContainerActiveRecord
   */
  public ContentContainerActiveRecord $contentContainer;

  public function run(): string
  {
    return $this->content;
  }
}
