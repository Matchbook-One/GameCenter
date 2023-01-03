<?php
declare(strict_types=1);

use humhub\modules\gamecenter\models\GameSearch;
use humhub\modules\gamecenter\widgets\GameGridView;
use humhub\modules\gamecenter\widgets\GameImageColumn;
use humhub\modules\gamecenter\widgets\GameTitleColumn;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var GameSearch         $searchModel
 * @var ActiveDataProvider $dataProvider
 */
?>
<h4><?= Yii::t('GamecenterModule.base', 'Overview') ?></h4>
<div class="help-block">
  <?= Yii::t('GamecenterModule.base', 'This overview contains a list of each game.'); ?>
</div>

<br/>
<?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/gamecenter/admin'])]); ?>
<div class="row">
  <div class="col-md-8">
    <div class="input-group">
      <?= Html::activeTextInput($searchModel, 'freeText', [
        'class'       => 'form-control',
        'placeholder' => Yii::t('GamecenterModule.base', 'Search by name, description, id or owner.')
      ]); ?>
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
      </span>
    </div>
  </div>
  <div class="col-md-4 spacesearch-visibilities">
    <?= Html::activeDropDownList($searchModel, 'visibility', GameSearch::getVisibilityAttributes(), [
      'class'              => 'form-control',
      'data-action-change' => 'ui.form.submit'
    ]); ?>
  </div>
</div>
<?php ActiveForm::end(); ?>


<div class="table-responsive">
  <?= GameGridView::widget(
    [
      'dataProvider' => $dataProvider,
      'summary'      => '',
      'columns'      => [
        ['class' => GameImageColumn::class],
        ['class' => GameTitleColumn::class],
        [
          'attribute' => 'achievementCount',
          'label'     => Yii::t('GamecenterModule.base', 'Achievements')
        ]
      ],
    ]
  ) ?>
</div>
