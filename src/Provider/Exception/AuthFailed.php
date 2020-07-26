<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\Provider\Exception;

use Throwable;

/**
 * This exception is a base exception when we cannot auth user on callback url
 */
abstract class AuthFailed extends \Jkbennemann\Common\Exception
{
    public function __construct($message = 'Auth failed', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
