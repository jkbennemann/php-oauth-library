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

class WordPress extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'wordpress';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://public-api.wordpress.com/rest/v1/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://public-api.wordpress.com/oauth2/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://public-api.wordpress.com/oauth2/token';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function prepareRequest(string $method, string $uri, array &$headers, array &$query, AccessTokenInterface $accessToken = null): void
    {
        if ($accessToken) {
            $headers['Authorization'] = "Bearer {$accessToken->getToken()}";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->request('GET', 'me/', [], $accessToken);

        $hydrator = new ArrayHydrator([
            'ID' => 'id',
            'avatar_URL' => 'pictureURL',
        ]);

        return $hydrator->hydrate(new User(), $response);
    }
}
