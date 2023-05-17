<?php

namespace fhnw\modules\gamecenter\components;

use DateTime;
use IntlCalendar;

class Period
{

  private DateTime $end;
  private DateTime $start;

  private function __construct($start, $end)
  {
    $this->start = $start;
    $this->end = $end;
  }

  public static function daily(): Period
  {
    $start = IntlCalendar::fromDateTime(self::getDate());

    $end = IntlCalendar::fromDateTime(self::getDate());
    $end->add(IntlCalendar::FIELD_DAY_OF_MONTH, 1);

    return new Period($start->toDateTime(), $end->toDateTime());
  }

  public static function month(): Period
  {
    $start = IntlCalendar::fromDateTime(self::getDate());
    $start->set(IntlCalendar::FIELD_DAY_OF_MONTH, 1);

    $end = IntlCalendar::fromDateTime(self::getDate());
    $end->set(IntlCalendar::FIELD_DAY_OF_MONTH, 1);
    $end->add(IntlCalendar::FIELD_MONTH, 1);

    return new Period($start->toDateTime(), $end->toDateTime());
  }

  public static function weekly(): Period
  {
    $start = IntlCalendar::fromDateTime(self::getDate());
    $weekday = $start->get(IntlCalendar::FIELD_DAY_OF_WEEK);
    $start->add(IntlCalendar::FIELD_DAY_OF_MONTH, -$weekday);

    $end = IntlCalendar::fromDateTime(self::getDate());
    $end->add(IntlCalendar::FIELD_DAY_OF_MONTH, 7 - $weekday);

    return new Period($start->toDateTime(), $end->toDateTime());
  }

  private static function getDate(): DateTime
  {
    $date = new DateTime();

    return $date->setTime(0, 0, 0, 0);
  }

  /**
   * @return \DateTime
   */
  public function getEnd(): DateTime
  {
    return $this->end;
  }

  /**
   * @return \DateTime
   */
  public function getStart(): DateTime
  {
    return $this->start;
  }

}