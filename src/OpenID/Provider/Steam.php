<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OpenID\Provider;

use Jkbennemann\Common\ArrayHydrator;
use Jkbennemann\Provider\AccessTokenInterface;
use Jkbennemann\Common\Entity\User;

class Steam extends \Jkbennemann\OpenID\AbstractProvider
{
    const NAME = 'steam';

    /**
     * {@inheritdoc}
     */
    public function getOpenIdUrl()
    {
        return 'https://steamcommunity.com/openid/id';
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.steampowered.com/';
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
    protected function parseUserIdFromIdentity($identity): string
    {
        preg_match(
            '/7[0-9]{15,25}/',
            $identity,
            $matches
        );

        return (string) $matches[0];
    }

    /**
     * {@inheritDoc}
     */
    public function prepareRequest(string $method, string $uri, array &$headers, array &$query, AccessTokenInterface $accessToken = null): void
    {
        $query['key'] = $this->consumer->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $query = [
            'steamids' => $accessToken->getUserId()
        ];

        $response = $this->request('GET', 'ISteamUser/GetPlayerSummaries/v0002/', $query, $accessToken);

        $hydrator = new ArrayHydrator([
            'steamid' => 'id',
            'personaname' => 'username',
            'realname' => 'fullname',
            'avatarfull' => 'pictureURL',
        ]);

        return $hydrator->hydrate(new User(), $response['response']['players'][0]);
    }
}
