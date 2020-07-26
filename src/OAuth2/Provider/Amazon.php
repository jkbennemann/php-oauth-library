<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OAuth2\Provider;

use Jkbennemann\Common\ArrayHydrator;
use Jkbennemann\Provider\AccessTokenInterface;
use Jkbennemann\Common\Entity\User;

class Amazon extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'amazon';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.amazon.com/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://www.amazon.com/ap/oa';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://api.amazon.com/auth/o2/token';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->request('GET', 'user/profile', [], $accessToken);

        $hydrator = new ArrayHydrator([
            'user_id' => 'id',
            'name' => 'firstname',
        ]);

        return $hydrator->hydrate(new User(), $response);
    }
}
