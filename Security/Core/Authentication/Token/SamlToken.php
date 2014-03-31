<?php

namespace BeSimple\SsoAuthBundle\Security\Core\Authentication\Token;

use BeSimple\SsoAuthBundle\Sso\Saml\Manager as SamlManager;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;


class SamlToken extends AbstractToken
{

    /** @var SamlManager */
    private $manager;

    /** @var string */
    private $samlResponse;

    /**
     * @param SamlManager $manager
     * @param string      $samlResponse
     * @param string      $username
     * @param array       $roles
     * @param array       $validationAttributes
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        SamlManager $manager,
        $samlResponse,
        $username = null,
        array $roles = array(),
        array $validationAttributes = array()
    ) {
        parent::__construct($roles);

        $this->manager = $manager;
        $this->samlResponse = $samlResponse;

        $this->setAttribute('saml:validation', $validationAttributes);

        if (null !== $username) {
            $this->setUser($username);
            parent::setAuthenticated(true);
        }
    }

    /**
     * @return string
     */
    public function getSamlResponse()
    {
        return $this->samlResponse;
    }

    /**
     * @return SamlManager
     */
    public function getManager()
    {
        return $this->manager;
    }

}
