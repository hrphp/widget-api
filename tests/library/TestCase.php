<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HrphpTest;
use Slim\Slim;
use There4\Slim\Test\WebTestCase;

class TestCase extends WebTestCase
{
    public function getSlimInstance()
    {
        $app = new Slim(array(
            'version' => '0.0.0',
            'debug' => true,
            'mode' => 'testing'
        ));
        require APPLICATION_PATH . '/src/application/config/application.php';
        return $app;
    }
}
