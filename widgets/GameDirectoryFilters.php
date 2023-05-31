<?php

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\libs\Html;
use humhub\modules\ui\widgets\DirectoryFilters;

/**
 * GameDirectoryFilters displays the filters on the directory games page
 *
 * @package GameCenter/Widgets
 */
class GameDirectoryFilters extends DirectoryFilters
{

  /**
   * @inheritdoc
   */
  public $pageUrl = '/gamecenter/games';

  /**
   * @param string $filter
   *
   * @return string
   * @static
   */
  public static function getDefaultValue(string $filter): string
  {
    return match ($filter) {
      'sort'  => 'name',
      default => parent::getDefaultValue($filter),
    };
  }

  /**
   * @return void
   */
  protected function initDefaultFilters(): void
  {
    $this->addFilter(
        'keyword',
        [
            'title'        => GameCenterModule::t('base', 'Find Games by their description or by their tags'),
            'placeholder'  => GameCenterModule::t('base', 'Search...'),
            'type'         => 'input',
            'wrapperClass' => 'col-md-6 form-search-filter-keyword',
            'afterInput'   => Html::submitButton('<span class="fa fa-search"></span>', ['class' => 'form-button-search']),
            'sortOrder'    => 100,
        ]
    );

    $this->addFilter(
        'sort',
        [
            'title'     => GameCenterModule::t('base', 'Sorting'),
            'type'      => 'dropdown',
            'options'   => [
                'name'  => GameCenterModule::t('base', 'By Name'),
                'newer' => GameCenterModule::t('base', 'Newest first'),
                'older' => GameCenterModule::t('base', 'Oldest first'),
            ],
            'sortOrder' => 200,
        ]
    );

    $this->addFilter(
        'connection',
        [
            'title'     => GameCenterModule::t('base', 'Status'),
            'type'      => 'dropdown',
            'options'   => [
                ''          => GameCenterModule::t('base', 'Any'),
                'member'    => GameCenterModule::t('base', 'Member'),
                'follow'    => GameCenterModule::t('base', 'Following'),
                'none'      => GameCenterModule::t('base', 'Neither..nor'),
                'separator' => '———————————',
                'archived'  => GameCenterModule::t('base', 'Archived'),
            ],
            'sortOrder' => 300,
        ]
    );
  }

}
