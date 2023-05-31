<?php
/**
 * @noinspection PhpMissingDocCommentInspection
 * @noinspection PhpEnforceDocCommentInspection
 */

namespace Unit\components;

use Codeception\Test\Unit;
use fhnw\modules\gamecenter\components\GameCenter;
use fhnw\modules\gamecenter\components\GameModule;
use JetBrains\PhpStorm\ArrayShape;

class GameCenterTest extends Unit
{

  public function testRegister()
  {
    $moduleID = 'test';
    $module = new class(['module' => 'test']) extends GameModule
    {

      #[ArrayShape([['name' => 'string', 'title' => 'string', 'description' => 'string', 'secret' => 'bool', 'show_progress' => 'bool']])]
      public function getAchievementConfig(): array
      {
        return [];
      }

      public function getLeaderboardConfig(): array { return []; }

      #[ArrayShape(['title' => 'string', 'description' => 'string', 'tags' => 'string[]'])]
      public function getGameConfig(): array
      {
        return [];
      }

      public function getGameUrl(): string { return 'url'; }

    };

    static::assertTrue(GameCenter::getInstance()->register($moduleID, $module));
  }

  public function testUnregister() {}

}
