<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->get('/widgets', function () use ($app) {
    $result = [
        ['id' => 1, 'name' => 'My Favorite Widget'],
        ['id' => 2, 'name' => 'Another Widget'],
        ['id' => 3, 'name' => 'Another Widget'],
        ['id' => 4, 'name' => 'Another Widget'],
        ['id' => 5, 'name' => 'Another Widget'],
    ];
    echo json_encode($result);
});

$app->get('/widgets/:id', function ($id) use ($app) {
    $result = ['id' => $id, 'name' => 'My Favorite Widget'];
    echo json_encode($result);
});
