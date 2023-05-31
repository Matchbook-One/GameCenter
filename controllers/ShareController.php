<?php

/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use fhnw\modules\gamecenter\components\RestController;
use fhnw\modules\gamecenter\models\Game;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\post\models\Post;
use humhub\modules\user\models\User;
use OpenApi\Attributes\{MediaType, Post as OAPost, RequestBody, Response, Schema};
use Yii;
use yii\base\Exception;
use yii\web\HttpException;
use yii\web\Response as WebResponse;

/**
 * Class ScoreController
 *
 * @package GameCenter/Controllers
 */
class ShareController extends RestController
{

  /**
   * Share
   *
   * @return WebResponse
   * @throws HttpException
   */
  #[OAPost(path: '/gamecenter/share', tags: ['Share'])]
  #[RequestBody(content: new MediaType('application/json', new Schema('#/components/schemas/ShareRequestBody')))]
  #[Response(response: 201, description: 'OK')]
  #[Response(response: 400, description: 'Invalid request')]
  public function actionIndex(): WebResponse
  {
    $this->forcePostRequest();
    $request = Yii::$app->request;

    $game = Game::findOne(['module' => $request->post('module')]);
    $url = $game->getModule()
                ->getGameUrl();
    $message = '# ' . $request->post('message');
    $message .= "\n\n";
    $message .= "via [GameCenter]({$url})";

    $contentContainer = ContentContainer::findOne(['class' => User::class, 'pk' => $this->getPlayerID()]);
    try {
      $post = new Post($contentContainer);
      $post->message = $message;
      $post->save();

      return $this->returnSuccess(statusCode: 201);
    }
    catch (Exception $e) {
      return $this->returnError();
    }
  }

}
