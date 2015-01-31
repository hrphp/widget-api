<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HrphpTest\Integration;

use HrphpTest\TestCase;

class PresoTest extends TestCase
{
    public function testHeaders()
    {
        $this->client->get('/widgets', ['preso' => 1]);
        $body = json_decode($this->client->response->body(), true);
        $this->assertSame($this->client->response->status(), $body['headers']['statusCode']);
    }
}
