<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\ui\menu\widgets\TabMenu;
use yii\helpers\Url;

/**
 * GameCenter Administration Menu
 *
 * @package GameCenter/Widgets
 */
class GameCenterMenu extends TabMenu
{

  /**
   * @inheritdoc
   * @return void
   */
  public function init(): void
  {
    $gamesLink = new MenuLink(
        [
            'label'     => GameCenterModule::t('base', 'Game'),
            'url'       => Url::toRoute(['/gamecenter/admin/index']),
            'sortOrder' => 100,
            'isActive'  => MenuLink::isActiveState('gamecenter', 'admin', 'index')
        ]
    );

    $achievementsLink = new MenuLink(
        [
            'label'     => GameCenterModule::t('base', 'Achievements'),
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
