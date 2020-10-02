<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Controller;

use BeSimple\SsoAuthBundle\Sso\Manager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * TrustedSsoController
 */
class TrustedSsoController extends Controller
{
    /**
     * loginAction
     *
     * @param  Manager                      $manager
     * @param  Request                      $request
     * @param  AuthenticationException|null $exception
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Manager $manager, Request $request, AuthenticationException $exception = null)
    {
        return $this->render(
            'BeSimpleSsoAuthBundle:TrustedSso:login.html.twig',
            array(
                'manager'   => $manager,
                'request'   => $request,
                'exception' => $exception,
            )
        );
    }

    /**
     * logoutAction
     * @param  Manager $manager
     * @param  Request $request
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function logoutAction(Manager $manager, Request $request)
    {
        return $this->render(
            'BeSimpleSsoAuthBundle:TrustedSso:logout.html.twig',
            array(
                'manager' => $manager,
                'request' => $request,
            )
        );
    }
}
