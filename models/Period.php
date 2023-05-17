<?php

namespace fhnw\modules\gamecenter\models;

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

  public static function month()
  {
    $date = new DateTime();
    $date = $date->setTime(0, 0, 0, 0);
    $start = IntlCalendar::fromDateTime($date);
    $start->set(IntlCalendar::FIELD_DAY_OF_MONTH, 1);

    $end = IntlCalendar::fromDateTime($date);
    $end->set(IntlCalendar::FIELD_DAY_OF_MONTH, 1);
    $end->add(IntlCalendar::FIELD_MONTH, 1);

    return new Period($start->toDateTime(), $end->toDateTime());
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