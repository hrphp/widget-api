<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Exception;

class RecordsNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Record(s) not found matching the provided criteria.');
    }
}
