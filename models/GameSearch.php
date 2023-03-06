<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Game Search for Administration
 *
 */
class GameSearch extends Game {

  public string $freeText = '';
  public int $achievementCount;

  /**
   * @inheritdoc
   * @return string
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
   * @return array
   */
  public function rules(): array {
    return [
      [['id', 'visibility'], 'integer'],
      [['freeText'], 'safe']
    ];
  }

  /**
   * @inheritdoc
   * @return array
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

    $query = self::find()
                 ->addSelect(['game.*', 'achievementCount' => $achievementCount]);

    $providerOptions = [
      'query'      => $query,
      'pagination' => ['pageSize' => 20],
    ];
    $dataProvider = new ActiveDataProvider($providerOptions);

    $sort = [
      'attributes' => [
        'id',
        'title',
        'achievementCount'
      ]
    ];
    $dataProvider->setSort($sort);

//    Yii::debug($dataProvider->query, 'fhnw\modules\gamecenter\models\GameSearch::search');

    // default visibility
    $this->visibility = Game::VISIBILITY_ALL;

    $this->load($params);

    if (!$this->validate()) {
      $query->emulateExecution();

      return $dataProvider;
    }

    // Freetext filters
    if (!empty($this->freeText)) {
      $query->andWhere(
        [
          'OR',
          ['like', 'game.name', $this->freeText],
          ['like', 'game.title', $this->freeText],
          ['like', 'game.description', $this->freeText]
        ]
      );
    }

    $query->andWhere(
      [
        'OR',
        ['game.visibility' => self::VISIBILITY_REGISTERED_ONLY],
        ['game.visibility' => self::VISIBILITY_ALL]
      ]
    );

    return $dataProvider;
  }

}
