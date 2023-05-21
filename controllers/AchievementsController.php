<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\models\Achievement;
use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\PlayerAchievement;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use OpenApi\Attributes\Schema;
use Yii;
use yii\base\Action;
use yii\web\Response as WebResponse;

/**
 * Class ScoreController
 *
 * @package GameCenter/Controllers
 * @property ?string $subLayout
 * @property string $pageTitle
 * @property array $actionTitlesMap
 * @property bool $prependActionTitles
 * @property class-string $access
 * @property array<string> $doNotInterceptActionIds
 * @property \humhub\components\View $view
 * @method init(): void
 * @method getAccessRules(): array
 * @method getAccess(): ?ControllerAccess
 * @method beforeAction(Action $action): void
 * @method behaviors(): array
 * @method renderAjaxContent(string $content): string
 * @method renderAjaxPartial(string $content): string
 * @method renderContent(string $content): string
 * @method forcePostRequest(): bool
 * @method htmlRedirect(string $url = '')
 * @method forbidden(): void
 * @method renderModalClose(): string
 * @method appendPageTitle(string $title): void
 * @method prependPageTitle(string $title): void
 * @method setPageTitle(string $title): void
 * @method setActionTitles(array $map = [], bool $prependActionTitles = true): void
 * @method redirect(array|string $url, int $statusCode = 302): Response
 * @method setJsViewStatus(): void
 * @method isNotInterceptedAction(string $actionId = null): bool
 */
class AchievementsController extends RestController
{

  private static function playerAchievement(PlayerAchievement $achievement): array
  {
    return [
      'percentCompleted' => $achievement->percent_completed,
      'lastUpdated'      => $achievement->lastReportedDate()
                                        ->format('c'),
      'achievement'      => $achievement->achievement->name,
      'game'             => $achievement->game->module
    ];
  }

  /**
   * Load Achievements
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[Post('/gamecenter/achievements/load', tags: ['Achievements'])]
  #[RequestBody(content: new MediaType('application/json', new Schema('#/components/schemas/ModuleRequestBody')))]
  #[Response(response: 200, description: 'OK', content: new JsonContent(
    type: 'array', items: new Items(
    ref: '#/components/schemas/Achievement'
  )
  ))]
  #[Response(response: 404, description: 'Not Found')]
  public function actionLoad(): WebResponse
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;
    $game = Game::findOne(['module' => $request->post('module')]);
    if (!$game) {
      return $this->returnError(404, 'Game not found');
    }
    /** @var array<PlayerAchievement> $achievements */
    $achievements = $this->loadAchievements($game->id, $this->getPlayerID());
    if (empty($achievements)) {
      $achievements = $this->create($game->id, $this->getPlayerID());
    }

    return $this->returnSuccess(additional: ['achievements' => $achievements]);
  }

  /**
   * Share
   *
   * @return \yii\web\Response
   * @throws \yii\web\HttpException
   */
  #[Post('/gamecenter/achievements/update', tags: ['Achievements'])]
  #[RequestBody(content: new MediaType('application/json', new Schema('#/components/schemas/AchievementRequestBody')))]
  #[Response(response: 200, description: 'OK')]
  #[Response(response: 400, description: 'Bad Request')]
  #[Response(response: 404, description: 'Not Found')]
  public function actionUpdate(): WebResponse
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;
    $game = Game::findOne(['module' => $request->post('module')]);
    if (!$game) {
      return $this->returnError(404, 'Game not found');
    }
    $achievement = $request->post('achievement');
    /** @var ?PlayerAchievement $pa */
    $pa = PlayerAchievement::find()
                           ->leftJoin(Achievement::tableName(), 'achievement_id')
                           ->where(['player_id' => $this->getPlayerID()])
                           ->andWhere(['achievement.name' => $achievement])
                           ->andWhere(['achievement.game_id' => $game->id])
                           ->one();

    if (!$pa) {
      return $this->returnError(404, 'Achievement not found');
    }
    $pa->percent_completed = $achievement['percentCompleted'];
    if ($pa->save()) {
      return $this->returnSuccess(additional: ['achievement' => $pa]);
    }
    else {
      return $this->returnError(additional: $pa->errors);
    }
  }

  /**
   * @param int $gameID
   * @param int $playerID
   *
   * @return \fhnw\modules\gamecenter\models\PlayerAchievement[]
   */
  private function create(int $gameID, int $playerID): array
  {
    /** @var array<Achievement> $ad */
    $ad = Achievement::find()
                     ->where(['game_id' => $gameID])
                     ->all();
    $achievements = [];
    foreach ($ad as $achievementDescription) {
      $achievement = new PlayerAchievement();
      $achievement->player_id = $playerID;
      $achievement->achievement_id = $achievementDescription->id;
      $achievement->save();
      $achievements[] = self::playerAchievement($achievement);
    }

    return $achievements;
  }

  /**
   * @param int $gameID
   * @param int $playerID
   *
   * @return array
   */
  private function loadAchievements(int $gameID, int $playerID): array
  {
    /** @var array<PlayerAchievement> $a */
    $a = PlayerAchievement::find()
                          ->leftJoin(Achievement::tableName(), 'achievement_id')
                          ->where(['player_id' => $playerID])
                          ->andWhere(['game_id' => $gameID])
                          ->all();

    return array_map(fn($achievement) => self::playerAchievement($achievement), $a);
  }

}
