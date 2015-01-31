<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Middleware;

use Slim\Middleware;

class PresoMiddleware extends Middleware
{
    public function call()
    {
        $this->next->call();
        if ($this->app->request()->params('preso') == 1) {
            $this->setResponse();
        }
    }

    private function setResponse()
    {
        $app = $this->app;
        $response = $app->response;
        $widgets = json_decode($response->getBody(), true);
        $headers = [
            'contentType' => $response->headers()->get('content-type'),
            'statusCode' => $response->getStatus(),
        ];
        $output = ['headers' => $headers, 'body' => $widgets];
        $response->setBody(json_encode($output));
    }
}
