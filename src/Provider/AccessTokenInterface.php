<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\Provider;

interface AccessTokenInterface
{
    /**
     * @return string|null
     */
    public function getToken();

    /**
     * @return string|null
     */
    public function getUserId();

    /**
     * @return integer|null
     */
    public function getExpires();
}
