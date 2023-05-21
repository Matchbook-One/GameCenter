<?php

/**
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter\controllers;

use humhub\components\Controller;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;

/**
 * @package GameCenter/Controllers
 */
abstract class ContentController extends Controller
{

  /** @var string $subLayout */
  public $subLayout = '@gamecenter/views/layouts/gamecenter';
  /** @var BaseDataProvider $dataProvider */
  protected BaseDataProvider $dataProvider;
  /** @var int current page */
  protected int $page = 0;

  /**
   * @throws \Throwable
   * @throws \yii\base\Exception
   */
  public function actionIndex(): string
  {
    $this->dataProvider = $this->loadPage();
    $models = $this->dataProvider->getModels();
    $items = $this->prepareInitialItems($models);

    return $this->renderItems($items);
  }

  protected function getPageSize(): int
  {
    /** @var \fhnw\modules\gamecenter\GameCenterModule $module */
    $module = $this->module;

    return $module->pageSize;
  }

  abstract protected function getPaginationQuery();

  protected function isLastPage($page = 0): bool
  {
    if (!$this->dataProvider->getPagination()) {
      return true;
    }

    return $this->dataProvider->getPagination()
                              ->getPageCount() <= $page + 1;
  }

  /**
   * @param int $page
   *
   * @return ActiveDataProvider
   * @throws \Throwable
   */
  protected function loadPage(int $page = 0): ActiveDataProvider
  {
    $query = $this->getPaginationQuery();

    return new ActiveDataProvider(
      [
        'query'      => $query,
        'pagination' => ['page' => $page, 'pageSize' => $this->getPageSize()]
      ]
    );
  }

  /**
   * This function can be overwritten by subclasses to add or manipulate the initial items array.
   *
   * @param \yii\base\Model[] $items
   *
   * @return \yii\base\Model[]
   */
  protected function prepareInitialItems(array $items): array { return $items; }

  /**
   * @param \yii\base\Model[] $items
   *
   * @return string
   */
  abstract protected function renderItems(array $items): string;

}