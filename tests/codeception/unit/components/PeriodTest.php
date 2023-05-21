<?php

namespace codeception\unit\components;

use Codeception\Test\Unit;
use DateInterval;
use fhnw\modules\gamecenter\components\Period;

class PeriodTest extends Unit
{

  public function testDaily()
  {
    $period = Period::daily();
    self::assertEquals(
      '00:00:00',
      $period->getStart()
             ->format('H:i:s')
    );
    self::assertEquals(
      '23:59:59',
      $period->getEnd()
             ->format('H:i:s')
    );

    $actual = $period->getDuration();
    $expected = new DateInterval('PT23H59M59S');
    static::assertEquals($expected, $actual);
  }

  public function testMonth()
  {
    $period = Period::month();
    $actual = $period->getDuration();
    $expectedDays = [27, 28, 29, 30];
    self::assertContains($actual->days, $expectedDays);

    self::assertEquals(
      '01',
      $period->getStart()
             ->format('d')
    );
    self::assertEquals(
      '00:00:00',
      $period->getStart()
             ->format('H:i:s')
    );
    self::assertEquals(
      '23:59:59',
      $period->getEnd()
             ->format('H:i:s')
    );

    self::assertEquals(
      $period->getStart()
             ->format('m'),
      $period->getEnd()
             ->format('m')
    );
  }

  public function testWeekly()
  {
    $period = Period::weekly();

    self::assertEquals(
      '1',
      $period->getStart()
             ->format('N')
    );
    self::assertEquals(
      '7',
      $period->getEnd()
             ->format('N')
    );
    self::assertEquals(
      '00:00:00',
      $period->getStart()
             ->format('H:i:s')
    );
    self::assertEquals(
      '23:59:59',
      $period->getEnd()
             ->format('H:i:s')
    );
    $actual = $period->getDuration();
    $expected = new DateInterval('P6DT23H59M59S');
    
    self::assertEquals($expected->d, $actual->d);
    self::assertEquals($expected->h, $actual->h);
    self::assertEquals($expected->i, $actual->i);
    self::assertEquals($expected->s, $actual->s);
  }

}
