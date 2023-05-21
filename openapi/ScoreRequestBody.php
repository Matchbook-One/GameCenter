<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class ScoreRequestBody extends ModuleRequestBody
{

  /** @var int */
  #[Property] public int $score;

}