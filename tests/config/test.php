<?php

use fhnw\modules\gamecenter\tests\fixtures\GameCenterFixture;

return [
    'modules'  => ['gamecenter'],
    'fixtures' => [
        'default',
        'task' => GameCenterFixture::class
    ]
];
