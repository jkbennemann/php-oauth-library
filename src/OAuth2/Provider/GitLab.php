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

class GitLab extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'gitlab';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://gitlab.com/api/v3/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://gitlab.com/oauth/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://gitlab.com/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public function getScopeInline()
    {
        return implode('+', $this->scope);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->request('GET', 'user', [], $accessToken);

        $hydrator = new ArrayHydrator([
            'user_id' => 'id',
            'name' => 'fullname',
            'avatar_url' => 'pictureURL'
        ]);

        return $hydrator->hydrate(new User(), $response);
    }
}
