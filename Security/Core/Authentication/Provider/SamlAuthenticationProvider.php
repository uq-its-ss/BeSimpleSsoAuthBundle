<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Security\Core\Authentication\Provider;

use BeSimple\SsoAuthBundle\Security\Core\Authentication\Token\SamlToken;
use BeSimple\SsoAuthBundle\Security\Core\User\SamlUserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class SamlAuthenticationProvider extends SsoAuthenticationProvider
{

    /** @var SamlUserProviderInterface */
    private $userProvider;

    /** @var UserCheckerInterface */
    private $userChecker;

    public function __construct(
        SamlUserProviderInterface $userProvider,
        UserCheckerInterface $userChecker,
        $createUsers = false,
        array $createdUsersRoles = array('ROLE_USER'),
        $hideUserNotFound = true
    ) {
        parent::__construct($userProvider, $userChecker, $createUsers, $createdUsersRoles, $hideUserNotFound);

        $this->userProvider = $userProvider;
        $this->userChecker = $userChecker;
    }

    /** {@inheritdoc} **/
    public function supports(TokenInterface $token)
    {
        return $token instanceof SamlToken;
    }

    /** {@inheritdoc} **/
    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            $ex = new AuthenticationException('Only SamlToken objects are supported');
            $ex->setToken($token);
            throw $ex;
        }

        $manager = $token->getManager();
        $validation = $manager->validateToken($token);
        if (!$validation->isSuccess()) {
            throw new BadCredentialsException('SAML validation error: '.$validation->getError());
        }

        $extractedUsername = $this->userProvider->extractUsername($validation->getUsername(), $validation->getAttributes());
        $user = $this->provideUser($extractedUsername, $validation->getAttributes());
        $this->userChecker->checkPreAuth($user);
        $this->userChecker->checkPostAuth($user);

        $authenticatedToken = new SamlToken(
            $token->getManager(),
            $token->getSamlResponse(),
            $user,
            $user->getRoles(),
            $validation->getAttributes()
        );
        foreach ($token->getAttributes() as $name => $value) {
            if ('saml:validation' === $name) {
                continue;
            }
            $authenticatedToken->setAttribute($name, $value);
        }

        return $authenticatedToken;
    }

}
