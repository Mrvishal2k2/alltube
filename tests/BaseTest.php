<?php

/**
 * PlaylistArchiveStreamTest class.
 */

namespace Alltube\Test;

use Alltube\Config;
use Alltube\Exception\ConfigException;
use PHPUnit\Framework\TestCase;

/**
 * Abstract class used by every test.
 */
abstract class BaseTest extends TestCase
{
    /**
     * Get the config file used in tests.
     *
     * @return string Path to file
     */
    protected function getConfigFile()
    {
        return __DIR__ . '/../config/config_test.yml';
    }

    /**
     * Prepare tests.
     * @throws ConfigException
     */
    protected function setUp(): void
    {
        Config::setFile($this->getConfigFile());
        $this->checkRequirements();
    }

    /**
     * Destroy properties after test.
     */
    protected function tearDown(): void
    {
        Config::destroyInstance();
    }

    /**
     * Check tests requirements.
     * @return void
     */
    protected function checkRequirements()
    {
        $annotations = $this->getAnnotations();
        $requires = [];

        if (isset($annotations['class']['requires'])) {
            $requires += $annotations['class']['requires'];
        }
        if (isset($annotations['method']['requires'])) {
            $requires += $annotations['method']['requires'];
        }

        foreach ($requires as $require) {
            if ($require == 'download' && getenv('CI')) {
                $this->markTestSkipped('Do not run tests that download videos on CI.');
            }
        }
    }
}
