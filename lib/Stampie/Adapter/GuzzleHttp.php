<?php

namespace Stampie\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response as GuzzleResponse;

/**
 * GuzzleHttp Adapter (guzzlephp.org)
 *
 * @author Henrik Bjornskov <henrik@bjrnskov.dk>
 */
class GuzzleHttp implements AdapterInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $endpoint
     * @param string $content
     * @param array $headers
     * @return Response
     */
    public function send($endpoint, $content, array $headers = array())
    {
        $request = $this->client->createRequest('POST', $endpoint, array(
            'body' => $content,
            'headers' => $headers,
        ));

        $response = $this->client->send($request);

        return new Response($response->getStatusCode(), $response->getBody(true));
    }
}
