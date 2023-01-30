<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\widgets;

use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\TabMenu;
use Yii;
use yii\helpers\Url;

/**
 * GameCenter Administration Menu
 *
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */
class GameCenterMenu extends TabMenu {

  /**
   * @inheritdoc
   * @return void
   */
  public function init(): void {

    $gamesLink = new MenuLink(
      [
        'label'     => Yii::t('GamecenterModule.base', 'Game'),
        'url'       => Url::toRoute(['/gamecenter/admin/index']),
        'sortOrder' => 100,
        'isActive'  => MenuLink::isActiveState('gamecenter', 'admin', 'index')
      ]
    );

    $achievementsLink = new MenuLink(
      [
        'label'     => Yii::t('GamecenterModule.base', 'Achievements'),
        'url'       => Url::toRoute(['/gamecenter/admin/achievements']),
        'sortOrder' => 200,
        'isActive'  => MenuLink::isActiveState('gamecenter', 'admin', 'achievements')
      ]
    );

    $this->addEntry($gamesLink);
    $this->addEntry($achievementsLink);

    parent::init();
  }
}