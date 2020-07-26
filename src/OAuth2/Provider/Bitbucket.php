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

class Bitbucket extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'bitbucket';

    /**
     * {@inheritdoc}
     */
    public function getBaseUri()
    {
        return 'https://api.bitbucket.org/2.0/';
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthorizeUri()
    {
        return 'https://bitbucket.org/site/oauth2/authorize';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTokenUri()
    {
        return 'https://bitbucket.org/site/oauth2/access_token';
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
        $response = $this->request('GET', 'user', [], $accessToken);

        $hydrator = new ArrayHydrator([
            'uuid' => 'id',
            'display_name' => 'fullname',
        ]);

        /** @var User $user */
        $user = $hydrator->hydrate(new User(), $response);
        $user->pictureURL = "https://bitbucket.org/account/{$user->username}/avatar/512/";

        if ($this->getBoolOption('fetch_emails', false)) {
            $primaryEmail = $this->getPrimaryEmail($accessToken);
            if ($primaryEmail) {
                $user->email = $primaryEmail['email'];
                $user->emailVerified = $primaryEmail['is_confirmed'];
            }
        }

        return $user;
    }

    /**
     * @param AccessTokenInterface $accessToken
     * @return array
     * @throws InvalidResponse
     * @throws ClientExceptionInterface
     */
    protected function getEmails(AccessTokenInterface $accessToken)
    {
        return $this->request('GET', 'user/emails', [], $accessToken);
    }

    /**
     * @param AccessTokenInterface $accessToken
     * @return array|null
     * @throws InvalidResponse
     * @throws ClientExceptionInterface
     */
    protected function getPrimaryEmail(AccessTokenInterface $accessToken)
    {
        $emails = $this->getEmails($accessToken);
        if ($emails && isset($emails['values'])) {
            foreach ($emails['values'] as $key => $value) {
                if (isset($value['is_primary']) && $value['is_primary']) {
                    return $value;
                }
            }
        }

        return null;
    }
}
