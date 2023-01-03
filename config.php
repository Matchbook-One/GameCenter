<?php
declare(strict_types=1);

use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\gamecenter\Events;
use humhub\widgets\TopMenu;

return [
  'id'        => 'gamecenter',
  'class'     => 'humhub\modules\gamecenter\Module',
  'namespace' => 'humhub\modules\gamecenter',
  'events'    => [
    [
      'class'    => TopMenu::class,
      'event'    => TopMenu::EVENT_INIT,
      'callback' => [Events::class, 'onTopMenuInit'],
    ],
    [
      'class'    => AdminMenu::class,
      'event'    => AdminMenu::EVENT_INIT,
      'callback' => [Events::class, 'onAdminMenuInit']
    ],
  ]
];
