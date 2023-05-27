<?php

namespace fhnw\modules\gamecenter\tests\unit\components;

use Codeception\Test\Unit;
use fhnw\modules\gamecenter\components\LeaderboardType;

class LeaderboardTypeTest extends Unit
{

  public function testNames()
  {
    self::assertEquals('CLASSIC', LeaderboardType::CLASSIC->name);
    self::assertEquals('RECURRING_DAILY', LeaderboardType::RECURRING_DAILY->name);
    self::assertEquals('RECURRING_WEEKLY', LeaderboardType::RECURRING_WEEKLY->name);
    self::assertEquals('RECURRING_MONTHLY', LeaderboardType::RECURRING_MONTHLY->name);
  }

}
