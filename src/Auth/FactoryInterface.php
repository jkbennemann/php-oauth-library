<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\Auth;

use Jkbennemann\OAuth1\AbstractProvider as OAuth1AbstractProvider;
use Jkbennemann\OAuth2\AbstractProvider as OAuth2AbstractProvider;
use Jkbennemann\Auth\Service;

interface FactoryInterface
{
    /**
     * @param string $id
     * @param array $parameters
     * @param Service $service
     * @return OAuth1AbstractProvider|OAuth2AbstractProvider
     */
    public function factory($id, array $parameters, Service $service);

    /**
     * @param string $id
     * @return boolean
     */
    public function has($id);
}
