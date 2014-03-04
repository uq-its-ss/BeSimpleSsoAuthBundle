<?php

namespace BeSimple\SsoAuthBundle\Sso\Saml;

use BeSimple\SsoAuthBundle\Sso\AbstractProtocol;
use Buzz\Client\ClientInterface;
use Buzz\Message\Request as BuzzRequest;
use Buzz\Message\Response as BuzzResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;


class Protocol extends AbstractProtocol
{

    /**
     * {@inheritdoc}
     */
    public function isValidationRequest(SymfonyRequest $request)
    {
        return $request->request->has('SAMLResponse');
    }

    /**
     * {@inheritdoc}
     */
    public function extractCredentials(SymfonyRequest $request)
    {
        return $request->request->get('SAMLResponse');
    }

    /**
     * {@inheritdoc}
     */
    public function executeValidation(ClientInterface $client, BuzzRequest $request, $credentials)
    {
        $validation = new Validation(new BuzzResponse(), $credentials);
        $validation->setSamlSettings(Util::createOneLoginSamlSettings($this));
        return $validation;
    }

}

