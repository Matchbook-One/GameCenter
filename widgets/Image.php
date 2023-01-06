<?php
declare(strict_types=1);

namespace fhnw\modules\gamecenter\widgets;

use fhnw\modules\gamecenter\models\Game;
use humhub\modules\ui\widgets\BaseImage;

/**
 * Return space image or acronym
 *
 * @since   1.0
 * @author  Christian Seiler
 * @package GameCenter
 * @property $htmlOptions
 * @property $linkOptions
 */
class Image extends BaseImage {
  /** @var Game */
  public Game $game;

  /**
   * @var int number of characters used in the acronym
   */
  public int $acronymCount = 2;

  /**
   * @inheritdoc
   */
  public function run() {
    if (!isset($this->linkOptions['href'])) {
      $this->linkOptions['href'] = $this->game->getUrl();
    }

    //if ($this->game->color != null) {
    //  $color = Html::encode($this->game->color);
    //} else {
    $color = '#d7d7d7';
    //}

    if (!isset($this->htmlOptions['class'])) {
      $this->htmlOptions['class'] = '';
    }

    if (!isset($this->htmlOptions['style'])) {
      $this->htmlOptions['style'] = '';
    }

    $acronymHtmlOptions = $this->htmlOptions;
    $imageHtmlOptions = $this->htmlOptions;

    $acronymHtmlOptions['class'] .= ' game-profile-acronym-' . $this->game->id . ' space-acronym';
    $acronymHtmlOptions['style'] .= ' background-color: ' . $color . '; width: ' . $this->width . 'px; height: ' . $this->height . 'px;';
    $acronymHtmlOptions['style'] .= ' ' . $this->getDynamicStyles($this->width);
    $acronymHtmlOptions['data-contentcontainer-id'] = $this->game->contentcontainer_id;

    $imageHtmlOptions['class'] .= ' space-profile-image-' . $this->game->id . ' img-rounded profile-user-photo';
    $imageHtmlOptions['style'] .= ' width: ' . $this->width . 'px; height: ' . $this->height . 'px';
    $imageHtmlOptions['alt'] = Html::encode($this->game->name);

    $imageHtmlOptions['data-contentcontainer-id'] = $this->game->contentcontainer_id;

    if ($this->showTooltip) {
      $this->linkOptions['data-toggle'] = 'tooltip';
      $this->linkOptions['data-placement'] = 'top';
      $this->linkOptions['data-html'] = 'true';
      $this->linkOptions['data-original-title'] = ($this->tooltipText) ? $this->tooltipText : Html::encode($this->game->name);
      Html::addCssClass($this->linkOptions, 'tt');
    }

    $defaultImage = true;
    //$defaultImage = (basename($this->space->getProfileImage()->getUrl()) == 'default_space.jpg' || basename($this->space->getProfileImage()->getUrl()) == 'default_space.jpg?cacheId=0') ? true : false;

    if (!$defaultImage) {
      $acronymHtmlOptions['class'] .= ' hidden';
    } else {
      $imageHtmlOptions['class'] .= ' hidden';
    }

    return $this->render('@gamecenter/widgets/views/image', [
      'game'               => $this->game,
      'acronym'            => $this->getAcronym(),
      'link'               => $this->link,
      'linkOptions'        => $this->linkOptions,
      'acronymHtmlOptions' => $acronymHtmlOptions,
      'imageHtmlOptions'   => $imageHtmlOptions
    ]);
  }

  /**
   * @param $elementWidth
   *
   * @return string
   */
  protected function getDynamicStyles($elementWidth): string {

    $fontSize = 44 * $elementWidth / 100;
    $padding = 18 * $elementWidth / 100;
    $borderRadius = 4;

    if ($elementWidth < 140 && $elementWidth > 40) {
      $borderRadius = 3;
    }

    if ($elementWidth < 35) {
      $borderRadius = 2;
    }

    return 'font-size: ' . $fontSize . 'px; padding: ' . $padding . 'px 0; border-radius: ' . $borderRadius . 'px;';
  }

  /**
   * @return string
   */
  protected function getAcronym(): string {
    $acronym = '';

    $gameName = preg_replace('/[^\p{L}\d\s]+/u', '', $this->game->name);

    foreach (explode(' ', $gameName) as $word) {
      if (mb_strlen($word) >= 1) {
        $acronym .= mb_substr($word, 0, 1);
      }
    }

    return mb_substr(mb_strtoupper($acronym), 0, $this->acronymCount);
  }

}
