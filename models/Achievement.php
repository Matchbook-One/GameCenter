<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for the table "achievement".
 *
 * @author     Christian Seiler
 * @version    1.0
 * @package    GameCanter
 * @property int    $id
 * @property string $guid
 * @property string $name
 * @property string $title
 * @property int    $game_id
 * @property array  $requirements
 * @property string $created_at
 * @property int    $created_by
 * @property string $updated_at
 * @property int    $updated_by
 */
class Achievement extends ActiveRecord {

  /**
   * @inheritdoc
   */
  public static function tableName(): string {
    return 'achievement';
  }

}