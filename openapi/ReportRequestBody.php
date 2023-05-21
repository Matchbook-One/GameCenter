<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class ReportRequestBody extends ModuleRequestBody
{

  /** @var string */
  #[Property] public string $option;
  /** @var string */
  #[Property] public string $value;

}


