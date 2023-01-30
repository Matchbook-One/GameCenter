<?php

/**
 * @since   1.0
 * @author  Christian Seiler
 * @package GameCenter
 */

namespace fhnw\modules\gamecenter;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Url;

/**
 * The GameCenter Module
 */
class Module extends ContentContainerModule {

  /** @var string */
  public $controllerNamespace = 'fhnw\modules\gamecenter\controllers';

  /**
   * @inheritdoc
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function getConfigUrl(): string {
    return Url::to(['/gamecenter/admin']);
  }

  /**
   * @inheritdoc
   *
   * @param ContentContainerActiveRecord $container unused
   *
   * @return  string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function getContentContainerDescription(ContentContainerActiveRecord $container): string {
    return Yii::t('GamecenterModule.base', 'Short description of the modules purpose.');
  }

  /**
   * @inheritdoc
   *
   * @param ContentContainerActiveRecord $container unused
   *
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function getContentContainerName(ContentContainerActiveRecord $container): string {
    return Yii::t('GamecenterModule.base', 'GameCenter');
  }

  /**
   * @inheritdoc
   * @return array
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function getContentContainerTypes(): array {
    return [Space::class];
  }
}
