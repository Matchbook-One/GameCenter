<?php

namespace fhnw\modules\gamecenter\components;

use fhnw\modules\gamecenter\models\Game;
use fhnw\modules\gamecenter\widgets\GameDirectoryFilters;
use Yii;
use yii\data\Pagination;

/**
 * @package GameCenter/Components
 */
class GameDirectoryQuery extends ActiveQueryGame
{

  /** @var int */
  public int $pageSize = 25;
  /** @var \yii\data\Pagination */
  public Pagination $pagination;

  /**
   * @inheritdoc
   *
   * @param array $config
   */
  public function __construct($config = [])
  {
    parent::__construct(Game::class, $config);
  }

  /** @return void */
  public function init(): void
  {
    parent::init();

    // $this->visible();

    // $this->filterBlockedSpaces();
    $this->filterByKeyword();
    // $this->filterByConnection();

    $this->order();

    $this->paginate();
  }

  /*
    public function filterByConnection(): GameDirectoryQuery
    {
      $connection = Yii::$app->request->get('connection');

      $this->filterByConnectionArchived($connection === 'archived');

      switch ($connection) {
        case 'member':
          return $this->filterByConnectionMember();
        case 'follow':
          return $this->filterByConnectionFollow();
        case 'none':
          return $this->filterByConnectionNone();
      }

      return $this;
    }

    public function filterByConnectionArchived(bool $showArchived = false): GameDirectoryQuery
    {
      return $this->andWhere('space.status ' . ($showArchived ? '=' : '!=') . ' :spaceStatus', [
        ':spaceStatus' => Space::STATUS_ARCHIVED,
      ]);
    }

    public function filterByConnectionFollow(): GameDirectoryQuery
    {
      return $this->innerJoin(
        'user_follow', 'user_follow.object_model = :spaceClass AND user_follow.object_id = space.id', [':spaceClass' => Space::class]
      )->andWhere(['user_follow.user_id' => Yii::$app->user->id]);
    }

    public function filterByConnectionMember(): GameDirectoryQuery
    {
      return $this->innerJoin('space_membership', 'space_membership.space_id = space.id')->andWhere(
        ['space_membership.user_id' => Yii::$app->user->id]
      )->andWhere(['space_membership.status' => Membership::STATUS_MEMBER]);
    }

    public function filterByConnectionNone(): GameDirectoryQuery
    {
      return $this->andWhere('space.id NOT IN (SELECT space_id FROM space_membership WHERE user_id = :userId AND status = :memberStatus)')
                  ->andWhere(
                    'space.id NOT IN (SELECT object_id FROM user_follow WHERE user_id = :userId AND user_follow.object_model = :spaceClass)'
                  )
                  ->addParams([
                                ':userId' => Yii::$app->user->id,
                                ':memberStatus' => Membership::STATUS_MEMBER,
                                ':spaceClass' => Space::class,
                              ]);
    }
  */
  /** @return $this */
  public function filterByKeyword(): GameDirectoryQuery
  {
    $keyword = Yii::$app->request->get('keyword', '');

    return $this->search($keyword);
  }

  /** @return bool */
  public function isLastPage(): bool
  {
    return $this->pagination->getPage() == $this->pagination->getPageCount() - 1;
  }

  /** @return $this */
  public function order(): GameDirectoryQuery
  {
    switch (GameDirectoryFilters::getValue('sort')) {
      case 'title':
        $this->addOrderBy('game.title');
        break;

      case 'newer':
        $this->addOrderBy('game.created_at DESC');
        break;

      case 'older':
        $this->addOrderBy('game.created_at');
        break;
    }

    return $this;
  }

  /** @return $this */
  public function paginate(): GameDirectoryQuery
  {
    $countQuery = clone $this;
    $this->pagination = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $this->pageSize]);

    return $this->offset($this->pagination->offset)
                ->limit($this->pagination->limit);
  }

}
