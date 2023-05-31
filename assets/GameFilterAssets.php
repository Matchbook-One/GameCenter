<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
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
   * @inheritdoc
   */
  public $js = ['js/humhub.gamecenter.GameFilter.js'];

  /** @inheritdoc */
  public string $sourcePath = '@gamecenter/resources';

}
