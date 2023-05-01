<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

use fhnw\modules\gamecenter\GameCenterModule;
use fhnw\modules\gamecenter\models\GameSearch;
use fhnw\modules\gamecenter\widgets\GameTitleColumn;
use humhub\modules\ui\view\components\View;
use humhub\widgets\GridView;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var GameSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var View $this
 */

?>

<h4><?= GameCenterModule::t('admin', 'Overview') ?></h4>
<div class="help-block">
  <?= GameCenterModule::t('admin', 'This overview contains a list of games.') ?>
</div>

<br/>
<?php
$form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['/gamecenter/admin/gamecenter'])]); ?>
<div class="row">
  <div class="col-md-8">
    <div class="input-group">
      <?= Html::activeTextInput(
        $searchModel, 'freeText', ['class' => 'form-control', 'placeholder' => GameCenterModule::t('admin', 'Search by...')]
      ) ?>
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </span>
    </div>
  </div>
</div>

<?php
ActiveForm::end(); ?>

<div class="table-responsive">
  <?= GridView::widget(
    [
      'dataProvider' => $dataProvider,
      'tableOptions' => ['class' => 'table table-hover'],
      'summary'      => '',
      'columns'      => [
        ['class' => GameTitleColumn::class],
        [
          'attribute' => 'achievementCount',
          'label'     => GameCenterModule::t('admin', 'Achievements')
        ]
      ],
    ]
  ) ?>
</div>
