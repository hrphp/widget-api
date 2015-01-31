<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Middleware;

use Slim\Middleware;

class JsonApiMiddleware extends Middleware
{
    public function call()
    {
        $this->next->call();
        $this->setResponse();
    }

    private function setResponse()
    {
        $app = $this->app;
        $app->response->headers->set('Content-Type', 'application/json');
        $body = json_decode($app->response->getBody(), true);
        $output['widgets'] = [];
        if (isset($body[0])) {
            foreach ($body as $widget) {
                $output['widgets'][] = $this->standardize($widget);
            }
        } else {
            $output['widgets'] = $this->standardize($body);
        }
        $app->response->setBody(json_encode($output));
    }

    private function standardize(array $data)
    {
        $request = $this->app->request;
        $data['href'] = sprintf(
            '%s://%s/widgets/%d',
            $request->getScheme(),
            $request->getHostWithPort(),
            $data['id']
        );
        return $data;
    }
}
