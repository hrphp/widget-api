<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HrphpTest\Integration;

use HrphpTest\TestCase;

class WidgetsTest extends TestCase
{
    public function testStatus()
    {
        $this->client->head('/widgets');
        $response = $this->client->response;
        $this->assertSame(200, $response->status());
    }

    public function testFindById()
    {
        $this->client->get('/widgets/1');
        $body = $this->client->response->body();
        $this->assertJson($body);
        $this->assertSame(1, (int) json_decode($body, true)['widgets']['id']);
    }

    public function testFindByOutOfRangeId()
    {
        $this->client->get('/widgets/999');
        $error = json_decode($this->client->response->body(), true);
        $this->assertSame(404, $this->client->response->status());
        $this->assertSame('Record(s) not found matching the provided criteria.', $error['error']);
    }

    public function testFindAll()
    {
        $this->client->get('/widgets', ['offset' => 0, 'limit' => 5]);
        $widgets = json_decode($this->client->response->body(), true);
        $this->assertSame(5, count($widgets['widgets']));
    }

    public function testDelete()
    {
        $this->client->delete('/widgets/5');
        $this->assertEmpty($this->client->response->body());
        $this->assertSame(204, $this->client->response->status());
    }
}
