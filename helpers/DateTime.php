<?php

namespace fhnw\modules\gamecenter\helpers;

class DateTime
{

  private const FORMAT = 'Y-m-d G:i:s';

  public static function date(string $datetime): \DateTime
  {
    return \DateTime::createFromFormat(format: static::FORMAT, datetime: $datetime);
  }

  public static function formatted(\DateTime $dateTime): string
  {
    return $dateTime->format(static::FORMAT);
  }

}