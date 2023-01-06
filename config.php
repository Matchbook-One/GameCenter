<?php
declare(strict_types=1);

use fhnw\modules\gamecenter\Events;
use humhub\modules\admin\widgets\AdminMenu;

return [
  'id'        => 'gamecenter',
  'class'     => 'fhnw\modules\gamecenter\Module',
  'namespace' => 'fhnw\modules\gamecenter',
  'events'    => [
    [
      'class'    => AdminMenu::class,
      'event'    => AdminMenu::EVENT_INIT,
      'callback' => [Events::class, 'onAdminMenuInit']
    ],
  ]
];
 