<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class ModuleRequestBody
{

  /** @var string $module The Module ID */
  #[Property]
  public string $module;

}

