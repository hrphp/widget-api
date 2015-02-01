<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// check for DB credentials
if (!getenv('CLEARDB_DATABASE_URL')) {
    die('Could not connect to the datasource.');
}

// set the working directory to the app root
chdir(dirname(dirname(__DIR__)));

// pull in dependencies
require 'vendor/autoload.php';

// instantiate the app
$app = new \Slim\Slim();

// configure the app
require 'src/application/config/application.php';

// run the app
$app->run();
