<?php
/**
 * Here you can overwrite the default config for the functional suite.
 * The default config resides in @humhubTests/codeception/config/config.php
 */

use tests\codeception\_support\HumHubTestConfiguration;

print('hello');
$suite = HumHubTestConfiguration::getSuiteConfig('functional');
print($suite);

return $suite;

