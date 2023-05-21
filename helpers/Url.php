<?php

namespace fhnw\modules\gamecenter\helpers;

use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\Player;
use humhub\modules\content\components\ContentContainerActiveRecord;

class Url extends \yii\helpers\Url
{

  public static function toAchievements(Game $game, Player $player): string
  {
    return self::to(['/gamecenter/games/achievements', 'gid' => $game->id, 'pid' => $player->id]);
  }

  public static function toGamesOverview(): string
  {
    return self::to('/gamecenter/games');
  }

  public static function toLeaderboards($id): string
  {
    return self::to(['/gamecenter/games/leaderboard', 'gid' => $id]);
  }

  public static function toLoadGamePage(ContentContainerActiveRecord $container): string { return $container->createUrl('load-page'); }

  public static function toModuleConfig(ContentContainerActiveRecord $container)
  {
    return $container->createUrl('/gamecenter/admin');
  }

}