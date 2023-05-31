<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\events;

use humhub\components\Event;

/**
 * @package GameCenter/Events
 * @property string  $name    the event name. This property is set by [[Component::trigger()]] and [[trigger()]].
 * @property ?object $sender  The sender of this event.
 * @property mixed   $data    the data that is passed to [[Component::on()]] when attaching an event handler.
 */
class GameEvent extends Event
{

}
