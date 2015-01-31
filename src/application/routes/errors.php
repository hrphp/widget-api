<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->error(function (\Exception $exception) {
    $code = $exception->getCode() > 200 ? $exception->getCode() : 500;
    throw new \Exception($exception->getMessage(), $code);
});
