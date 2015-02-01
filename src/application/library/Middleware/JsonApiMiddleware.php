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
        $app = $this->app;
        $app->response->headers->set('Content-Type', 'application/json');
        try {
            $this->next->call();
            $this->setResults();
        } catch (\Exception $ex) {
            $this->setError($ex);
        }
    }

    private function setError(\Exception $ex)
    {
        $type = str_replace('Hrphp\Exception\\', '', get_class($ex));
        $code = 500;
        switch($type) {
            case 'RecordsNotFoundException':
                $code = 404;
                break;
        }
        $this->app->response->setStatus($code);
        $this->app->response->setBody(json_encode(['error' => $ex->getMessage()]));
        ob_end_clean();
    }

    private function setResults()
    {
        if (!$body = $this->app->response->getBody()) {
            return;
        }

        $widgets = json_decode($body, true);
        $output['widgets'] = [];
        if (isset($widgets[0])) {
            foreach ($widgets as $widget) {
                $output['widgets'][] = $this->standardize($widget);
            }
        } else {
            $output['widgets'] = $this->standardize($widgets);
        }
        $this->app->response->setBody(json_encode($output));
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
