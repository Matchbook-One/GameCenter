<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class ShareRequestBody extends ModuleRequestBody
{

  #[Property]
  public string $message;

}