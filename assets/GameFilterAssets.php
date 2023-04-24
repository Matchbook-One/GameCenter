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
 */
class GameFilterAssets extends AssetBundle
{

  /**
   * @inheritdoc
   */
  public $js = ['js/gamecenter.filter.js'];
  public $sourcePath = '@gamecenter/resources';
}
