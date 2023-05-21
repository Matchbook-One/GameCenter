<?php

namespace fhnw\modules\gamecenter\widgets;

use Exception;
use fhnw\modules\gamecenter\models\PlayerAchievement;
use humhub\components\Widget;
use Yii;

class AchievementCard extends Widget
{

  public PlayerAchievement $achievement;

  public static function with(PlayerAchievement $achievement): static
  {
    return new static(['achievement' => $achievement]);
  }

  /**
   * @return string
   */
  public function __toString()
  {
    try {
      $result = $this::widget($this->getWidgetOptions());

      return $result ?: '';
    }
    catch (Exception $e) {
      Yii::error($e);
    }

    return '';
  }

  /**
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run(): string
  {
    $config = [
      'achievement' => $this->achievement
    ];

    return $this->render('achievementCard', $config);
  }

  private function getWidgetOptions(): array
  {
    return [
      'achievement' => $this->achievement,
      'id'          => $this->id
    ];
  }

}