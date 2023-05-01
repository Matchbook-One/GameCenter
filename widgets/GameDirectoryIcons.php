<?php
/**
 * from humhub\modules\space\widgets\SpaceDirectoryIcons.php
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\models\Play;
use humhub\components\Widget;
use Yii;

/**
 */
class GameDirectoryIcons extends Widget
{
  public Game $game;

  /**
   * @inheritdoc
   */
  public function run()
  {
    /*
    if ($this->gamw->getAdvancedSettings()->hideMembers) {
      return '';
    }

    $membership = $this->space->getMembership();
    $membersCountQuery = Membership::getSpaceMembersQuery($this->space)->active();
    if (Yii::$app->user->isGuest) {
      $membersCountQuery->andWhere(['!=', 'user.visibility', User::VISIBILITY_HIDDEN]);
    } else {
      $membersCountQuery->visible();
    }

*/
    $playerCountQuery = Play::getPlayedGamesQuery($this->game);
    $formatter = Yii::$app->formatter;

    return $this->render(
      'gameDirectoryIcons',
      [
        'game'           => $this->game,
        'canViewPlayers' => false,
        'playerCount'    => $formatter->asShortInteger($playerCountQuery->count())
        //'canViewMembers' => $membership && $membership->isPrivileged(),
      ]
    );
  }

}
