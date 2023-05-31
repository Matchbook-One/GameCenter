<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\activities;

use humhub\modules\activity\components\BaseActivity;

/**
 * @package GameCenter/Activities
 */
class GameActivity extends BaseActivity
{

  /** @var string $moduleId the module id which this activity belongs to (required) */
  public string $moduleId = 'example';

  /** @var string $viewName view name used for rendering the activity */
  public string $viewName = 'somethingHappend';

}
