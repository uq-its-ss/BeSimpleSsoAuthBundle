<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Tests;

use Buzz\Client\BuzzClientInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * HttpClient
 */
class HttpClient implements BuzzClientInterface
{
    protected static $kernel;

    /**
     * setKernel
     *
     * @param Kernel $kernel
     */
    public static function setKernel(Kernel $kernel)
    {
        static::$kernel = $kernel;
    }

    /**
     * send
     *
     * @param  BuzzRequest  $buzzRequest
     * @param  BuzzResponse $buzzResponse
     */
    public function send(RequestInterface $buzzRequest, MessageInterface $buzzResponse)
    {
        $session  = session_id();
        $request  = Request::create($buzzRequest->getUrl(), $buzzRequest->getMethod());
        $response = static::$kernel->handle($request);

        $buzzResponse->setContent($response->getContent());

        // kernel handling set session_id to empty string
        session_id($session);
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeout($timeout)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxRedirects($maxRedirects)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request, array $options = []): ResponseInterface
    {
    }
}
