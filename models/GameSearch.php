<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Game Search for Administration
 */
class GameSearch extends Game
{

    /** @var string $freeText */
    public $freeText;

    /**
     * @inheritdoc
     * @return array[]
     */
    public function rules(): array
    {
        $rules  = parent::rules();
        $rules += [
            [['freeText'], 'safe']
        ];

        return [];
    }

    /**
     * @inheritdoc
     * @return       array|array[]
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function scenarios(): array
    {
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
        $achievementCount = AchievementDescription::find()->select('COUNT(*) as counter')->where('game_id=game.id');

        $query = self::find()->addSelect(['game.*', 'achievementCount' => $achievementCount]);

        $providerOptions = [
            'query'      => $query,
            'pagination' => ['pageSize' => 20]
        ];
        $dataProvider    = new ActiveDataProvider($providerOptions);

        $sort = ['attributes' => ['id', 'title', 'achievementCount']];
        $dataProvider->setSort($sort);

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
        }

        return $dataProvider;
    }
}
