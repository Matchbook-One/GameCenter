<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use DateInterval;
use DateTime;

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
    return new Period(new DateTime('today'), new DateTime('midnight tomorrow -1 sec'));
  }

  public static function month(): Period
  {
    return new Period(new DateTime('midnight first day of'), new DateTime('midnight first day of next month -1 sec'));
  }

  public static function weekly(): Period
  {
    return new Period(new DateTime('midnight monday this week'), new DateTime('monday next week -1 sec'));
  }

  public function getDuration(): DateInterval
  {
    return $this->start->diff($this->end);
  }

  /**
   * @return \DateTime
   */
  public function getEnd(): DateTime
  {
    return $this->end;
  }

  public function getEndDate(): string
  {
    return $this->end->format($this->getFormat());
  }

  private function getFormat(): string
  {
    return 'D, d M Y';
  }

  /**
   * @return \DateTime
   */
  public function getStart(): DateTime
  {
    return $this->start;
  }

  public function getStartDate(): string
  {
    return $this->start->format($this->getFormat());
  }

}
