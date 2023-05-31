<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\components;

use yii\i18n\MissingTranslationEvent;

use function str_starts_with;

/**
 * @package GameCenter/Components
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
    if (str_starts_with($event->category, 'gamecenter')) {
      $event->translatedMessage = "@MISSING: '$event->category.$event->message' FOR LANGUAGE '$event->language'";
    }
  }

}
