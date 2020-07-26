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

class Facebook extends \Jkbennemann\OAuth2\AbstractProvider
{
    const NAME = 'facebook';

    public function getBaseUri()
    {
        return 'https://graph.facebook.com/v3.3/';
    }

    public function getAuthorizeUri()
    {
        return 'https://www.facebook.com/dialog/oauth';
    }

    public function getRequestTokenUri()
    {
        return 'https://graph.facebook.com/oauth/access_token';
    }

    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $query = [];

        $fields = $this->getArrayOption('identity.fields', []);
        if ($fields) {
            $query['fields'] = implode(',', $fields);
        }

        $response = $this->request(
            'GET',
            'me',
            $query,
            $accessToken
        );

        $hydrator = new ArrayHydrator([
            'id' => 'id',
            'first_name' => 'firstname',
            'last_name' => 'lastname',
            'email' => 'email',
            'gender' => static function ($value, User $user) {
                $user->setSex($value === 1 ? User::SEX_FEMALE : User::SEX_MALE);
            },
            'birthday' => static function ($value, User $user) {
                $user->setBirthday(
                    new \DateTime($value)
                );
            },
            'link' => 'url',
            'locale' => 'locale',
            'name' => 'fullname',
            'timezone' => 'timezone',
            'updated_time' => 'dateModified',
            'verified' => 'verified',
            'picture.data.url' => 'pictureURL'
        ]);

        /** @var User $user */
        $user = $hydrator->hydrate(new User(), $response);
        $user->emailVerified = true;

        return $user;
    }
}
