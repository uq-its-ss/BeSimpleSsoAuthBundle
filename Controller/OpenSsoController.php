<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Controller;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * OpenSsoController
 */
class OpenSsoController extends Controller
{
    /**
     * loginAction
     *
     * @throws HttpException
     */
    public function loginAction()
    {
        throw new HttpException(500, 'Not implemented.');
    }
    /**
     * logoutAction
     *
     * @throws HttpException
     */
    public function logoutAction()
    {
        throw new HttpException(500, 'Not implemented.');
    }
}
