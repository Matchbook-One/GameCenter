<?php

use fhnw\modules\gamecenter\tests\codeception\fixtures\GameCenterFixture;

return [
  'modules'  => ['gamecenter'],
  'fixtures' => [
    'default',
    'task' => GameCenterFixture::class
  ]
];



