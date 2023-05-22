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
      return $this::widget($this->getWidgetOptions());
    }
    catch (Exception $e) {
      Yii::error($e);

      return '';
    }
  }

  /**
   * @return string
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run(): string
  {
    $config = [
      'completed'     => $this->achievement->isCompleted(),
      'title'         => $this->achievement->getTitle(),
      'description'   => $this->achievement->getDescription(),
      'icon'          => $this->achievement->isCompleted() ? 'glass' : 'lock',
      'iconOptions'   => $this->getIconOptions($this->achievement->isCompleted()),
      'updated_at'    => $this->achievement->updated_at,
      'progress'      => $this->achievement->percent_completed,
      'show_progress' => $this->achievement->showProgress()
    ];

    return $this->render('achievementCard', $config);
  }

  private function getIconOptions($completed): array
  {
    return [
      'tooltip'     => $completed ? 'unlocked' : 'locked',
      'htmlOptions' => ['class' => 'img-fluid rounded-start']
    ];
  }

  private function getWidgetOptions(): array
  {
    return [
      'achievement' => $this->achievement,
      'id'          => $this->id
    ];
  }

}