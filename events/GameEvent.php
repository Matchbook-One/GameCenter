<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\events;

use humhub\components\Event;

/**
 * @package GameCenter/Events
 * @property string  $name    the event name. This property is set by [[Component::trigger()]] and [[trigger()]].
 * @property ?object $sender  the sender of this event. If not set, this property will be set as the object whose `trigger()` method is
 *           called. This property may also be a `null` when this event is a class-level event which is triggered in a static context.
 * @property bool    $handled whether the event is handled. Defaults to `false`. When a handler sets this to be `true`, the event
 *           processing will stop and ignore the rest of the uninvoked event handlers.
 * @property mixed   $data    the data that is passed to [[Component::on()]] when attaching an event handler.
 * @method on(string $class, string $name, callable $handler, mixed $data = null, bool $append = true): void
 * @method off(string $class, string $name, ?callable $handler = null): void
 * @method offAll(): void
 * @method trigger(object|string $class, string $name, ?Event $event = null): void
 * @method hasHandlers(object|string $class, string $name): bool
 */
class GameEvent extends Event
{
}
