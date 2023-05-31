<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\helpers;

use humhub\modules\content\components\ContentContainerActiveRecord;
use JetBrains\PhpStorm\ArrayShape;
use Yii;

use function str_starts_with;

class Url extends \yii\helpers\Url
{

  private const API_URL_PREFIX = 'gamecenter/api';

  /**
   * @return void
   * @static
   */
  public static function prepareAPIRules(): void
  {
    $request = Yii::$app->request;

    if (isset($request->pathInfo) && str_starts_with($request->pathInfo, self::API_URL_PREFIX)) {
      $rules = [
          ['pattern' => '/', 'route' => 'gamecenter/docs/api'],
          ['pattern' => '/<module>/score', 'route' => 'gamecenter/score/view', 'verb' => ['GET']],
          ['pattern' => '/<module>/score', 'route' => 'gamecenter/score/create', 'verb' => ['POST']],
          ['pattern' => '/<module>/highscore', 'route' => 'gamecenter/score/view', 'verb' => ['GET']],
          ['pattern' => '/<module>/leaderboard', 'route' => 'gamecenter/score/view', 'verb' => ['GET']],
          ['pattern' => '/<module>/achievement', 'route' => 'gamecenter/achievement/view', 'verb' => ['GET']],
          ['pattern' => '/share', 'route' => 'gamecenter/share', 'verb' => ['POST']]
      ];
      self::addRules($rules);
      //  Yii::warning(Json::encode(Yii::$app->urlManager->rules, JSON_PRETTY_PRINT), __METHOD__);
    }
  }

  /**
   * Add REST API endpoint rules
   *
   * @param array $rules
   */
  #[ArrayShape(['pattern' => 'string', 'route' => 'string', 'verb' => 'array'])]
  public static function addRules(array $rules): void
  {
    // from the HumHub/Rest Module
    foreach ($rules as $r => $rule) {
      if (isset($rule['pattern'])) {
        $rules[ $r ]['pattern'] = self::API_URL_PREFIX . $rule['pattern'];
      }
    }

    Yii::$app->urlManager->addRules($rules);
  }

  /**
   * @param int $id
   *
   * @return string
   * @static
   */
  public static function toAchievement(int $id): string
  {
    return self::to(["/gamecenter/achievement/$id"]);
  }

  public static function toAchievements(int $game_id, int $player_id): string
  {
    return self::to(['/gamecenter/games/achievements', 'game' => $game_id, 'pid' => $player_id]);
  }

  public static function toGamesOverview(): string
  {
    return self::to('/gamecenter/games');
  }

  public static function toLeaderboard(int $id): string
  {
    return self::to(["/gamecenter/leaderboard/$id"]);
  }

  public static function toLeaderboards($game_id): string
  {
    return self::to(['/gamecenter/games/leaderboard', 'game' => $game_id]);
  }

  public static function toLoadGamePage(ContentContainerActiveRecord $container): string
  {
    return $container->createUrl('load-page');
  }

  public static function toModuleConfig(ContentContainerActiveRecord $container): string
  {
    return $container->createUrl('/gamecenter/admin');
  }

}
