<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OAuth2\Provider;

use Jkbennemann\Common\ArrayHydrator;
use Jkbennemann\Provider\AccessTokenInterface;
use Jkbennemann\Provider\Exception\InvalidAccessToken;
use Jkbennemann\OAuth2\AccessToken;
use Jkbennemann\Common\Entity\User;

class Vimeo extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'vimeo';

    /**
     * @var User|null
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.vimeo.com/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://api.vimeo.com/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://api.vimeo.com/oauth/access_token';
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
    public function parseToken(string $body)
    {
        if (empty($body)) {
            throw new InvalidAccessToken('Provider response with empty body');
        }

        $response = json_decode($body, true);
        if ($response) {
            $token = new AccessToken($response);

            // Vimeo return User on get Access Token Request (looks like to protect round trips)
            if (isset($response['user'])) {
                $hydrator = new ArrayHydrator([
                    'name' => 'fullname',
                ]);

                $this->user = $hydrator->hydrate(new User(), $response['user']);
                $this->user->id = str_replace('/users/', '', $this['user']['uri']);

                $token->setUserId((string) $this->user->id);
            }

            return $token;
        }

        throw new InvalidAccessToken('AccessToken is not a valid JSON');
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        return $this->user;
    }
}
