<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 * @author Alexander Fedyashov <a@fedyashov.com>
 */
declare(strict_types=1);

namespace Jkbennemann\OpenIDConnect\Provider;

use Jkbennemann\Common\ArrayHydrator;
use Jkbennemann\Common\Entity\User;
use Jkbennemann\Common\Exception\InvalidArgumentException;
use Jkbennemann\Common\Exception\Unsupported;
use Jkbennemann\OpenIDConnect\AccessToken;
use Jkbennemann\Provider\AccessTokenInterface;
use Jkbennemann\OpenIDConnect\AbstractProvider;

/**
 * @link https://developer.apple.com/sign-in-with-apple/get-started/
 */
class Apple extends AbstractProvider
{
    const NAME = 'apple';

    /**
     * {@inheritdoc}
     */
    public function getOpenIdUrl()
    {
        throw new Unsupported('Apple does not support openid-configuration url');
    }

    /**
     * {@inheritDoc}
     */
    public function discover(): array
    {
        return [
            'jwks_uri' => 'https://appleid.apple.com/auth/keys'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://appleid.apple.com/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://appleid.apple.com/auth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://appleid.apple.com/auth/token';
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
    public function extractIdentity(AccessTokenInterface $accessToken)
    {
        if (!$accessToken instanceof AccessToken) {
            throw new InvalidArgumentException(
                '$accessToken must be instance AccessToken'
            );
        }

        $jwt = $accessToken->getJwt();

        $hydrator = new ArrayHydrator([
            'sub' => 'id',
            'email' => 'email',
            'email_verified' => 'emailVerified'
        ]);

        /** @var User $user */
        $user = $hydrator->hydrate(new User(), $jwt->getPayload());

        return $user;
    }

    /**
     * I didnt find any API
     *
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        return $this->extractIdentity($accessToken);
    }
}
