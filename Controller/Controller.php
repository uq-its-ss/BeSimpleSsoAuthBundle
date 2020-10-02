<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller
 */
class Controller extends ContainerAware
{
    /**
     * render
     *
     * @param  string $view
     * @param  array  $parameters
     *
     * @return Symfony\Component\HttpFoundation\Response
     */
    protected function render($view, array $parameters)
    {
        return $this
            ->container
            ->get('templating')
            ->renderResponse($view, $parameters)
        ;
    }
}
