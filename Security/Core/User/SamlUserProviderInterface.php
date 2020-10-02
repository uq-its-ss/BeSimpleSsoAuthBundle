<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Security\Core\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;

interface SamlUserProviderInterface extends UserProviderInterface
{

    /**
     * Extract username from the given values.
     *
     * @param string $nameId Name ID from SAML response.
     * @param array $attributes Attributes provided by SAML response.
     *
     * @return string
     */
    public function extractUsername($nameId, array $attributes);

}
