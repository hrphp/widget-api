<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Hrphp\Mapper\WidgetMapper;

$app->get('/widgets', function () use ($app) {
    $offset = $app->request->get('offset');
    $limit = $app->request->get('limit');
    if ($keyword = $app->request->get('q')) {
        $result = WidgetMapper::findByKeyword($keyword, $offset, $limit);
    } else {
        $result = WidgetMapper::findAll($offset, $limit);
    }
    echo json_encode($result);
});

$app->get('/widgets/:id', function ($id) use ($app) {
    $result = WidgetMapper::findById($id);
    echo json_encode($result);
})->conditions(['id' => '\d+']);

$app->put('/widgets', function ($id) use ($app) {
    $data = $app->request->getBody();
    $result = WidgetMapper::create($id, $data);
    echo json_encode($result);
});

$app->post('/widgets/:id', function ($id) use ($app) {
    $data = $app->request->getBody();
    if (!$result = WidgetMapper::update($id, $data)) {
        $app->error(new \Exception('Could not create the widget.', 400));
    }
    $app->response->setStatus(204);
    echo json_encode($result);
})->conditions(['id' => '\d+']);

$app->delete('/widgets/:id', function ($id) use ($app) {
    if (!WidgetMapper::delete($id)) {
        $app->error(new \Exception('Could not delete the widget.', 400));
    }
    $app->response->setStatus(204);
})->conditions(['id' => '\d+']);
