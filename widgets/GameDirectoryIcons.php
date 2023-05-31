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
   * @noinspection PhpMissingParentCallCommonInspection
   */
  public function run()
  {
    /*
    if ($this->game->getAdvancedSettings()->hideMembers) {
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
    /** @var \humhub\components\i18n\Formatter $formatter */
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
