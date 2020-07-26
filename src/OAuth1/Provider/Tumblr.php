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

class Tumblr extends AbstractProvider
{
    const NAME = 'tumblr';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.tumblr.com/v2/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://www.tumblr.com/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://www.tumblr.com/oauth/request_token';
    }

    /**
     * @return string
     */
    public function getRequestTokenAccessUri()
    {
        return 'https://www.tumblr.com/oauth/access_token';
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
            'user/info',
            [],
            $accessToken
        );

        if (!isset($result['response'], $result['response']['user']) || !$result['response']['user']) {
            throw new InvalidResponse(
                'API response without user inside JSON'
            );
        }

        $hydrator = new ArrayHydrator([
            'name' => 'id'
        ]);

        return $hydrator->hydrate(new User(), $result['response']['user']);
    }
}
