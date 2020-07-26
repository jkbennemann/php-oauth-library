<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OAuth2\Exception;

use Throwable;

class UnknownAuthorization extends \Jkbennemann\Provider\Exception\AuthFailed
{
    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = 'Unknown authorization', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
