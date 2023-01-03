<?php
declare(strict_types=1);

namespace humhub\modules\gamecenter\models;

use yii\db\ActiveRecord;


/**
 * This is the model class for the table "achievement".
 *
 * @property int    $id
 * @property string $guid
 * @property string $name
 * @property Game   $game
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