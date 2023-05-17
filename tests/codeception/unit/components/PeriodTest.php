<?php

namespace codeception\unit\components;

use Codeception\Test\Unit;
use DateTime;
use fhnw\modules\gamecenter\components\Period;

class PeriodTest extends Unit
{

  public function testDaily()
  {
    $period = Period::daily();

    static::assertEquals(
      1,
      $period->getStart()
             ->diff($period->getEnd())->days
    );
  }

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

  public function testWeekly()
  {
    $period = Period::weekly();

    self::assertLessThan(
      7,
      $period->getStart()
             ->diff(new DateTime())->days
    );

    self::assertLessThan(
      7,
      $period->getEnd()
             ->diff(new DateTime())->days
    );

    static::assertEquals(
      7,
      $period->getStart()
             ->diff($period->getEnd())->days
    );
  }

}
