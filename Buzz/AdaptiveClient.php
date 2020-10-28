<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Buzz;

use Buzz\Client\BuzzClientInterface;
use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class AdaptiveClient implements BuzzClientInterface
{
    private $client;
    private $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = $options;
        $this->client = function_exists('curl_init') ? new Curl() : new FileGetContents();
    }

    /**
     * send
     * @param  RequestInterface $request
     * @param  MessageInterface $response
     */
    public function send(RequestInterface $request, MessageInterface $response)
    {
        $this->client->send($request, $response, $this->options);
    }
}
