<?php

namespace Ephemeral;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\RequestInterface;
use Mockery\CountValidator\Exception;


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
        } catch (Exception $e) {
            dump("Something had happened");
            dump($e);
        }
        return $response;
    }
}