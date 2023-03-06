<?php

/**
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 */

namespace fhnw\modules\gamecenter\widgets;

use humhub\components\ActiveRecord;
use humhub\libs\Helpers;
use Yii;
use yii\bootstrap\Html;

/**
 * GameTitleColumn
 *
 * @author  Christian Seiler
 * @package GameCenter
 * @since   1.0
 * @property ?string $attribute
 * @property ?string $label
 */
class GameTitleColumn extends GameBaseColumn {

  /**
   * @inheritdoc
   */
  public function init() {
    parent::init();

    if ($this->attribute === null) {
      $this->attribute = 'title';
    }

    if ($this->label === null) {
      $this->label = Yii::t('GamecenterModule.base', 'Title');
    }
  }

  /**
   * @inheritdoc
   *
   * @param ActiveRecord $model the data model
   * @param string       $key   the key associated with the data model
   * @param int          $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
   */
  protected function renderDataCellContent($model, $key, $index): string {
    $game = $this->getGame($model);

    $badge = '';

    /*if ($game->status == Game::STATUS_ARCHIVED) {
      $badge = '&nbsp;<span class="badge">' . Yii::t('SpaceModule.base', 'Archived') . '</span>';
    }*/

    return '<div>' . Html::encode($game->title) . $badge . '<br> ' .
      '<small>' . Html::encode(Helpers::trimText($game->description, 100)) . '</small></div>';
  }

}