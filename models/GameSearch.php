<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use function array_merge;

/**
 * Game Search for Administration
 *
 * @package GameCenter
 */
class GameSearch extends Game
{
  /**
   * @var \yii\db\ActiveQuery query
   */
  public $query;

  /** @var string $freeText */
  public $freeText;

  /**
   * Returns the list of all attribute names of the model.
   * The default implementation will return all column names of the table associated with this AR class.
   *
   * @return array list of attribute names.
   */
  public function attributes()
  {
    return array_merge(parent::attributes(), []);
  }

  /**
   * @return array
   */
  public function rules(): array
  {
    return [
      ['id', 'integer'],
      [['module', 'title', 'description'], 'safe']
    ];
  }

  /**
   * @return array|array[]
   */
  public function scenarios()
  {
    // bypass scenarios() implementation in the parent class
    return Model::scenarios();
  }

  /**
   * Creates data provider instance with search query applied
   *
   * @param string[] $params The Search parameter
   *
   * @return ActiveDataProvider
   */
  public function search(array $params): ActiveDataProvider
  {
    $query = ($this->query == null) ? Game::find() : $this->query;

    $achievementCount = AchievementDescription::find()->select('COUNT(*) as counter')->where('game_id=game.id');
    /** @var \fhnw\modules\gamecenter\components\ActiveQueryGame $query */
    $query->addSelect(['game.*', 'achievementCount' => $achievementCount]);

    $providerOptions = [
      'query'      => $query,
      'pagination' => ['pageSize' => 20]
    ];

    $dataProvider = new ActiveDataProvider($providerOptions);
    $sort = ['attributes' => ['title']];
    $dataProvider->setSort($sort);
    $dataProvider->sort->defaultOrder = ['title' => SORT_ASC];

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
          ['like', 'game.module', $this->freeText],
          ['like', 'game.title', $this->freeText],
          ['like', 'game.description', $this->freeText]
        ]
      );
      if (!empty($this->status)) {
        $query->andFilterWhere(['game.status' => $this->status]);
      }

      return $dataProvider;
    }

    return $dataProvider;
  }
}
