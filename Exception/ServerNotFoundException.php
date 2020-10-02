<?php
/**
 * Forked and maintained by The University of Queensland
 */

namespace BeSimple\SsoAuthBundle\Exception;

/**
 * @author: Jean-François Simon <contact@jfsimon.fr>
 */
class ServerNotFoundException extends NotFoundException
{
    public function __construct($id, $code = null, $previous = null)
    {
        parent::__construct($id, 'Server', $code, $previous);
    }
}
