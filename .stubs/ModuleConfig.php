<?php

namespace fhnw\humhub\stubs;

/**
 * @package HumHub/stubs
 * @property-read int                               $id                   Unique module ID
 * @property-read class-string                      $class                Namespaced classname of the module class
 * @property-read string                            $namespace            The namespace of your module
 * @property-read ?array<EventConfig>               $events               array containing the modules event configuration
 * @property-read ?array<\yii\web\UrlRuleInterface> $urlManagerRules      array of URL Manager Rules
 * @property-read ?array<string>                    $modules              Can be used to define submodules
 * @property-read ?array<string, class-string>      $consoleControllerMap list of console controllers
 */
interface ModuleConfig
{
}

/**
 * @package HumHub/stubs
 * @property class-string                         $class    The namespaced class string of the class responsible for triggering the event.
 * @property string                               $event    The event name, usually available as class const
 * @property array<class-string, callable-string> $callback Event handler callback class and function name
 */
interface EventConfig
{
}
