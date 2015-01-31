<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hrphp\Middleware;
$app->add(new Middleware\JsonApiMiddleware());
$app->add(new Middleware\PresoMiddleware());
