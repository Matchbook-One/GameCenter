<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class AchievementResponse extends GameCenterResponse
{

  /** @var Achievement achievement */
  #[Property]
  public Achievement $achievement;

}