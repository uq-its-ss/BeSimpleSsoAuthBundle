<?php

namespace BeSimple\SsoAuthBundle\Tests;

use Buzz\Client\BuzzClientInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements BuzzClientInterface
{
    static protected $kernel;

    static public function setKernel(Kernel $kernel)
    {
        static::$kernel = $kernel;
    }

    public function send(RequestInterface $buzzRequest, MessageInterface $buzzResponse)
    {
        $session  = session_id();
        $request  = Request::create($buzzRequest->getUrl(), $buzzRequest->getMethod());
        $response = static::$kernel->handle($request);

        $buzzResponse->setContent($response->getContent());

        // kernel handling set session_id to empty string
        session_id($session);
    }

    public function setTimeout($timeout)
    {
    }

    public function setMaxRedirects($maxRedirects)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request, array $options = []): ResponseInterface
    {
    }
}
