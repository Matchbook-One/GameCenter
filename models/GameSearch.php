<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Game Search for Administration
 *
 * @author     Christian Seiler
 * @version    1.0
 * @package    GameCanter
 */
class GameSearch extends Game {

  public string $freeText = '';

  /**
   * @inheritdoc
   */
  public static function className(): string {
    return Game::class;
  }

  /**
   * @return string[]
   */
  public static function getVisibilityAttributes(): array {
    $countPublic = Game::find()
                       ->where(['visibility' => self::VISIBILITY_ALL])
                       ->orWhere(['visibility' => self::VISIBILITY_REGISTERED_ONLY])
                       ->count();


    return [
      Game::VISIBILITY_REGISTERED_ONLY => Yii::t('GamecenterModule.base', 'Public') . ' (' . $countPublic . ')',
    ];
  }

  /**
   * @inheritdoc
   */
  public function rules(): array {
    return [
      [['id', 'visibility'], 'integer'],
      [['freeText'], 'safe']
    ];
  }

  /**
   * @inheritdoc
   */
  public function scenarios(): array {
    return Model::scenarios();
  }

  /**
   * Creates data provider instance with search query applied
   *
   * @param array $params
   *
   * @return ActiveDataProvider
   */
  public function search(array $params): ActiveDataProvider {
    $achievementCount = Achievement::find()
                                   ->select('COUNT(*) as counter')
                                   ->where('game_id=game.id');
    $query = self::find();
    $query->addSelect(['game.*', 'achievementCount' => $achievementCount]);

    $providerOptions = [
      'query'      => $query,
      'pagination' => ['pageSize' => 50],
    ];
    $dataProvider = new ActiveDataProvider($providerOptions);

    $sort = [
      'attributes' => [
        'id',
        'title',
        'visibility'
      ]
    ];
    $dataProvider->setSort($sort);
//    $dataProvider->sort->attributes['ownerUser.profile.lastname'] = [
//      'asc'  => ['profile.lastname' => SORT_ASC],
//      'desc' => ['profile.lastname' => SORT_DESC],
//    ];


    // default visibility
    $this->visibility = Game::VISIBILITY_ALL;

    $this->load($params);

    if (!$this->validate()) {
      $query->emulateExecution();
      return $dataProvider;
    }


    // Freetext filters
    if (!empty($this->freeText)) {
      $query->andWhere([
                         'OR',
                         ['like', 'game.name', $this->freeText],
                         ['like', 'game.title', $this->freeText],
                         ['like', 'game.description', $this->freeText]
                       ]);
    }

    $query->andWhere([
                       'OR',
                       ['game.visibility' => self::VISIBILITY_REGISTERED_ONLY],
                       ['game.visibility' => self::VISIBILITY_ALL]
                     ]);

    return $dataProvider;
  }
}