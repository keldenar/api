<?php

namespace Ephemeral;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\RequestInterface;



class ApiService {

    private $app;

    public function __construct($app) {
        $this->app = $app;
        $this->client = new Client();
    }

    public function post($service, $endpoint, $payload) {
        dump($payload);
        $this->client->setBaseUrl($this->app['config']->get("apis.". $service . ".url"));
        $request = $this->client->post($endpoint, ['content-type' => 'application/json'] ,array($payload));
        $request->addHeader('Authorization', 'Bearer ' . $this->app['oauth2']->token());
        dump($request);
        try {
            $response = $request->send();
        } catch (\Exception $e) {
            dump("Something had happened");
            dump($e);
        }
        return $response;
    }

    public function get($service, $endpoint, $payload) {
        $this->client->setBaseUrl($this->app['config']->get("apis.". $service . ".url"));
        if ($payload != null) {
            if (is_array($payload)) {
                $new = "";
                foreach ($payload as $key => $value) {
                    $new = sprintf("%s&%s=%s", $new, $key, $value);
                }
                $new = ltrim($new, "&");
                $endpoint = sprintf("%s?%s", $endpoint, $new);
            }  else {
                $endpoint = sprintf("%s?%s", $endpoint, $payload);
            }
        }
        $request = $this->client->get($endpoint);
        $request->addHeader('Authorization', 'Bearer ' . $this->app['oauth2']->token());
        dump($request);
        try {
            $response = $request->send();
        } catch (\Exception $e) {
            dump("Something had happened");
            dump($e);
        }
        return $response;
    }
}