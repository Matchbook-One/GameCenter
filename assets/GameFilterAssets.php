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
class GameFilterAssets extends AssetBundle {

  public $sourcePath = '@gamecenter/resources';

  /**
   * @inheritdoc
   */
  public $js = ['js/humhub.gamecenter.GameFilter.js'];
}
