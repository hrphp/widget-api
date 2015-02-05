<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Middleware;

use Slim\Exception\Stop;
use Slim\Middleware;

class JsonApiMiddleware extends Middleware
{
    public function call()
    {
        $app = $this->app;
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->headers->set('Access-Control-Allow-Origin', '*');
        try {
            $this->next->call();
            $this->setResults();
            if ($app->response->isNotFound()) {
                $app->notFound();
            }
        } catch (\InvalidArgumentException $ex) {
            $this->setError($ex->getMessage(), 400);
        } catch (\DomainException $ex) {
            $this->setError($ex->getMessage(), 404);
        } catch (Stop $ex) {
            $this->setError('Endpoint not supported.', 404);
        } catch (\Exception $ex) {
            $code = $ex->getCode() > 200 ? $ex->getCode() : 500;
            $this->setError($ex->getMessage(), $code);
        }
    }

    private function setError($message, $code)
    {
        $this->app->response->setStatus($code);
        $this->app->response->setBody(json_encode(['error' => $message]));
        ob_end_clean();
    }

    private function setResults()
    {
        if (!$body = $this->app->response->getBody()) {
            return;
        }

        $widgets = json_decode($body, true);
        $output['widgets'] = [];
        if (!empty($widgets)) {
            if (isset($widgets[0])) {
                foreach ($widgets as $widget) {
                    $output['widgets'][] = $this->standardize($widget);
                }
            } else {
                $output['widgets'] = $this->standardize($widgets);
            }
        }
        $this->app->response->setBody(json_encode($output));
    }

    private function standardize(array $data)
    {
        $request = $this->app->request;
        $data['href'] = sprintf(
            '%s://%s/widgets/%d',
            $request->getScheme(),
            $request->getHost(),
            $data['id']
        );
        return $data;
    }
}
