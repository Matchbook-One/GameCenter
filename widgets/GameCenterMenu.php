<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\widgets;

use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\TabMenu;
use Yii;
use yii\helpers\Url;

/**
 * GameCenter Administration Menu
 *
 * @since   1.0
 * @author  Christian Seiler
 * @package GameCenter
 */
class GameCenterMenu extends TabMenu {

  /**
   * @inheritdoc
   */
  public function init() {

    $this->addEntry(new MenuLink([
                                   'label'     => Yii::t('GamecenterModule.base', 'Game'),
                                   'url'       => Url::toRoute(['/gamecenter/admin/index']),
                                   'sortOrder' => 100,
                                   'isActive'  => MenuLink::isActiveState('gamecenter', 'game', 'index')
                                 ]));

    parent::init();
  }

}