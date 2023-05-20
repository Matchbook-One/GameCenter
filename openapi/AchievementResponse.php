<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class AchievementResponse
{

  /** @var Achievement[] achievements */
  #[Property]
  public array $achievements;

}