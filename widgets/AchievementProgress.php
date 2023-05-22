<?php

namespace fhnw\modules\gamecenter\widgets;

use Throwable;
use Yii;
use yii\bootstrap\Progress;

class AchievementProgress extends Progress
{

  public static function make(int $progress, bool $showProgress): string
  {
    try {
      return Progress::widget(
        [
          'percent'    => self::getProgress($progress, $showProgress),
          'barOptions' => ['class' => self::getStyle($progress, $showProgress)],
          'options'    => ['class' => 'progress-striped']
        ]
      );
    }
    catch (Throwable $e) {
      Yii::error($e);

      return '';
    }
  }

  private static function getProgress(int $progress, bool $showProgress): int
  {
    $showProgress = $progress === 100 | $showProgress;

    return $showProgress ? $progress : 0;
  }

  private static function getStyle(int $progress, bool $showProgress): string
  {
    $green = 'progress-bar-success';
    $blue = 'progress-bar-info';
    $yellow = 'progress-bar-warning';
    $red = 'progress-bar-danger';
    if ($progress === 100) {
      return $green;
    }
    if ($showProgress) {
      return $blue;
    }

    return $yellow;
  }

}