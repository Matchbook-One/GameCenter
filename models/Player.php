<?php

namespace fhnw\modules\gamecenter\models;

use humhub\modules\user\models\User;
use OpenApi\Attributes\Schema;
use yii\db\ActiveQuery;

/**
 * @property int $id
 * @property string $guid
 * @property int $status
 * @property string $username
 * @property string $email
 * @property string $language
 * @property string $time_zone
 * @property \humhub\modules\user\models\Profile $profile
 * @property string $displayName
 * @property string $displayNameSub
 * @property-read \fhnw\modules\gamecenter\models\Game[] $games
 * @property-read \fhnw\modules\gamecenter\models\PlayerAchievement[] $achievements
 */
#[Schema]
class Player extends User
{

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getAchievements(): ActiveQuery
  {
    return $this->hasMany(
      PlayerAchievement::class,
      ['id' => 'player_id']
    );
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getGames(): ActiveQuery
  {
    return $this->hasMany(Game::class, []);
  }

}
