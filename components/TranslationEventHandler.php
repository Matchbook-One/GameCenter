<?php

namespace fhnw\modules\gamecenter\components;

use yii\i18n\MissingTranslationEvent;

/**
 *
 */
class TranslationEventHandler
{
    /**
     * @param MissingTranslationEvent $event
     *
     * @return void
     * @static
     */
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = "@MISSING: $event->category.$event->message FOR LANGUAGE {$event->language} @";
    }
}
