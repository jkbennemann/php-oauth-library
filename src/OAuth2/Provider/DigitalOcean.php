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

class DigitalOcean extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'digital-ocean';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.digitalocean.com/v2/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://cloud.digitalocean.com/v1/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://cloud.digitalocean.com/v1/oauth/token';
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
        $response = $this->request('GET', 'account', [], $accessToken);

        $hydrator = new ArrayHydrator([
            'uuid' => 'id',
        ]);

        return $hydrator->hydrate(new User(), $response['account']);
    }
}
