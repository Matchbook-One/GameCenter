<?php

namespace codeception\unit;

use Codeception\Test\Unit;
use fhnw\modules\gamecenter\models\Period;

class PeriodTest extends Unit
{

  public function testMonth()
  {
    $period = Period::month();

    static::assertEquals(
      '01',
      $period->getStart()
             ->format('d')
    );
    static::assertEquals(
      '01',
      $period->getEnd()
             ->format('d')
    );

    static::assertNotEquals(
      $period->getStart()
             ->format('m'),
      $period->getEnd()
             ->format('m')
    );
  }

}
