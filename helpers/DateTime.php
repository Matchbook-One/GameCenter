<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\helpers;
/**
 * @package GameCenter/Helpers
 */
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
