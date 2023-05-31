<?php
/**
 * @author Christian Seiler <christian@christianseiler.ch>
 * @since  1.0.0
 */

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use humhub\components\Widget;
use humhub\libs\Html;
use yii\helpers\Url;

/**
 * @package GameCenter/Widgets
 */
class GameDirectoryTagList extends Widget
{

  public Game $game;

  /**
   * @var int number of max. displayed tags
   */
  public int $maxTags = 5;

  /**
   * @var string Template for tags
   */
  public string $template = '{tags}';

  /**
   * @inheritdoc
   */
  public function run()
  {
    $html = '';

    $tags = $this->game->getTags();

    $count = count($tags);

    if ($count === 0) {
      return $html;
    } elseif ($count > $this->maxTags) {
      $tags = array_slice($tags, 0, $this->maxTags);
    }

    foreach ($tags as $tag) {
      if (trim($tag) !== '') {
        $html .= Html::a(Html::encode($tag), Url::to(['/gamecenter/games', 'keyword' => trim($tag)]), ['class' => 'label label-default']) .
            "&nbsp";
      }
    }

    if ($html === '') {
      return $html;
    }

    return str_replace('{tags}', $html, $this->template);
  }

}
