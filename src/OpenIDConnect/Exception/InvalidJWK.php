<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 * @author Alexander Fedyashov <a@fedyashov.com>
 */
declare(strict_types=1);

namespace Jkbennemann\OpenIDConnect\Exception;

use Exception;

class InvalidJWK extends \Jkbennemann\Common\Exception
{
    public function __construct($message = 'Not Valid JWK', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
