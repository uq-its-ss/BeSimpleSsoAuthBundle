<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Tests;

use Buzz\Client\ClientInterface;
use Buzz\Message\RequestInterface as BuzzRequest;
use Buzz\Message\MessageInterface as BuzzResponse;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HttpClient
 */
class HttpClient implements ClientInterface
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
    public function send(BuzzRequest $buzzRequest, BuzzResponse $buzzResponse)
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
}
