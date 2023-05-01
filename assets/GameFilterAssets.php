<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\assets;

use humhub\components\assets\AssetBundle;

/**
 * GameFilterAssets
 *
 * @package GameCenter/Assets
 */
class GameFilterAssets extends AssetBundle
{

  /**
   * @var array<string> $js
   * @inheritdoc
   */
  public $js = ['js/gamecenter.filter.js'];
  /** @var string $sourcePath */
  public $sourcePath = '@gamecenter/resources';
}
