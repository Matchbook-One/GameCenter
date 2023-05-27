<?php

/**
 * Here you can initialize variables via \Codeception\Util\Fixtures class
 * to store data in global array and use it in Tests.
 * ```php
 * // Here _bootstrap.php
 * \Codeception\Util\Fixtures::add('user1', ['name' => 'davert']);
 * ```
 * In Tests
 * ```php
 * \Codeception\Util\Fixtures::get('user1');
 * ```
 */

/**
 * Initialize the HumHub Application for functional testing. The default application configuration for this suite can be overwritten
 * in @tests/config/functional.php
 */
require(Yii::getAlias('@humhubTests/codeception/functional/_bootstrap.php'));