<?php

namespace humhub\modules\fhnw\gamecenter;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Url;

class Module extends ContentContainerModule {
  /**
   * @inheritdoc
   */
  public function getContentContainerTypes(): array {
    return [
      Space::class
    ];
  }

  /**
   * @inheritdoc
   */
  public function getConfigUrl(): string {
    return Url::to(['/gamecenter/admin']);
  }

  /**
   * @inheritdoc
   */
  public function disable(): void {
    // Cleanup all module data, don't remove the parent::disable()!!!
    parent::disable();
  }

  /**
   * @inheritdoc
   */
  public function disableContentContainer(ContentContainerActiveRecord $container) {
    // Clean up space related data, don't remove the parent::disable()!!!
    parent::disableContentContainer($container);
  }

  /**
   * @inheritdoc
   */
  public function getContentContainerName(ContentContainerActiveRecord $container): string {
    return Yii::t('GamecenterModule.base', 'Game Center');
  }

  /**
   * @inheritdoc
   */
  public function getContentContainerDescription(ContentContainerActiveRecord $container): string {
    return Yii::t('GamecenterModule.base', 'Short description of the modules purpose.');
  }
}