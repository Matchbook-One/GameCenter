<?php

namespace fhnw\modules\gamecenter\openapi;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema]
class Achievement
{

  #[Property]
  public string $achievement;
  #[Property]
  public string $game;
  #[Property(format: 'date-time')]
  public string $lastUpdated;
  #[Property(maximum: 100, minimum: 0)]
  public int $percentCompleted;

}