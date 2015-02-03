<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HrphpTest\Integration;

use Hrphp\Entity\WidgetMapper;
use HrphpTest\TestCase;

class WidgetsTest extends TestCase
{
    public function testStatus()
    {
        $this->client->head('/widgets');
        $response = $this->client->response;
        $this->assertSame(200, $response->status());
    }

    public function testFindAll()
    {
        $this->client->get('/widgets', ['offset' => 0, 'limit' => 5]);
        $widgets = json_decode($this->client->response->body(), true);
        $this->assertSame(200, $this->client->response->status());
        $this->assertSame(5, count($widgets['widgets']));
    }

    public function testFindByKeyword()
    {
        $this->client->get('/widgets', ['q' => 'widget']);
        $widgets = json_decode($this->client->response->body(), true);
        $this->assertSame(200, $this->client->response->status());
        $this->assertSame(5, count($widgets['widgets']));
    }

    public function testFindByOutOfRangeOffset()
    {
        $this->client->get('/widgets', ['offset' => 1000]);
        $body = json_decode($this->client->response->body(), true);
        $this->assertSame(200, $this->client->response->status());
        $this->assertEmpty($body['widgets']);
    }

    public function testFindById()
    {
        $this->client->get('/widgets/1');
        $body = $this->client->response->body();
        $this->assertJson($body);
        $this->assertSame(200, $this->client->response->status());
        $this->assertSame(1, (int) json_decode($body, true)['widgets']['id']);
    }

    public function testFindByOutOfRangeId()
    {
        $this->client->get('/widgets/999');
        $this->assertSame(404, $this->client->response->status());
    }

    public function testCreate()
    {
        $this->client->put('/widgets', ['name' => 'That Widget', 'color' => 'orange']);
        $widgets = json_decode($this->client->response->body(), true);
        $this->assertSame(201, $this->client->response->status());
        $this->assertSame('orange', $widgets['widgets']['color']);
    }

    public function testCreateWithInvalidData()
    {
        $invalidData = ['name' => null, 'color' => 'orange'];
        $this->client->put('/widgets', $invalidData);
        $error = json_decode($this->client->response->body(), true);
        $this->assertSame(400, $this->client->response->status());
        $this->assertSame(WidgetMapper::MSG_INVALID_DATA, $error['error']);
    }

    public function testUpdate()
    {
        $data = ['name' => 'A Better Widget', 'color' => 'blackish'];
        $this->client->post('/widgets/1', $data);
        $widgets = json_decode($this->client->response->body(), true);
        $this->assertSame(200, $this->client->response->status());
        $this->assertSame($data['name'], $widgets['widgets']['name']);
        $this->assertSame($data['color'], $widgets['widgets']['color']);
    }

    public function testUpdateWithInvalidData()
    {
        $invalidData = ['tag' => 'test'];
        $this->client->post('/widgets/1', $invalidData);
        $this->assertSame(400, $this->client->response->status());
    }

    public function testUpdateWithOutOfRangeId()
    {
        $this->client->post('/widgets/999', ['color' => 'blackish']);
        $error = json_decode($this->client->response->body(), true);
        $this->assertSame(404, $this->client->response->status());
        $this->assertSame(WidgetMapper::MSG_INVALID_ID, $error['error']);
    }

    public function testDelete()
    {
        $this->client->delete('/widgets/5');
        $this->assertSame(204, $this->client->response->status());
    }

    public function testDeleteWithInvalidOutOfRangeId()
    {
        $this->client->delete('/widgets/999');
        $error = json_decode($this->client->response->body(), true);
        $this->assertSame(404, $this->client->response->status());
        $this->assertSame(WidgetMapper::MSG_INVALID_ID, $error['error']);
    }
}
