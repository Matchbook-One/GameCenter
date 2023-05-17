<?php
/**
 * GameCenterAssets.php
 *
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\assets;

use humhub\components\assets\AssetBundle;
use yii\web\View;

/**
 * AssetsBundles are used to include assets as javascript or css files.
 *
 * @package GameCenter/Assets
 * @author  Christian Seiler <christian@christianseiler.ch>
 */
class GameCenterAssets extends AssetBundle
{

  /**
   * @var string[] $js
   * @inheritdoc
   */
  public $js = ['js/gamecenter.js'];

  /**
   * @var array{position: int} $jsOptions
   * @inheritdoc
   */
  public $jsOptions = ['position' => View::POS_END];

  /**
   * @var array{forceCopy: bool} $publishOptions
   * @inheritdoc
   */
  public $publishOptions = ['forceCopy' => true];

  /**
   * @var string $sourcePath
   * @inheritdoc
   */
  public $sourcePath = '@gamecenter/resources';

}
