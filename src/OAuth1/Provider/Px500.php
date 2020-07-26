<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OAuth1\Provider;

use Jkbennemann\Common\ArrayHydrator;
use Jkbennemann\Provider\AccessTokenInterface;
use Jkbennemann\Provider\Exception\InvalidResponse;
use Jkbennemann\OAuth1\AbstractProvider;
use Jkbennemann\Common\Entity\User;

class Px500 extends AbstractProvider
{
    const NAME = 'px500';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.500px.com/v1/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://api.500px.com/v1/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://api.500px.com/v1/oauth/request_token';
    }

    /**
     * @return string
     */
    public function getRequestTokenAccessUri()
    {
        return 'https://api.500px.com/v1/oauth/access_token';
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
        $this->consumerToken = $accessToken;

        $result = $this->request(
            'GET',
            'users',
            [],
            $accessToken
        );

        if (!isset($result['user']) || !$result['user']) {
            throw new InvalidResponse(
                'API response without user inside JSON'
            );
        }

        $hydrator = new ArrayHydrator([
            'id' => 'id',
            'name' => 'name',
        ]);

        return $hydrator->hydrate(new User(), $result['user']);
    }
}
