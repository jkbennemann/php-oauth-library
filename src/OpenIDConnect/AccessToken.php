<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OpenIDConnect;

use SocialConnect\JWX\JWT;
use Jkbennemann\Provider\Exception\InvalidAccessToken;

class AccessToken extends \Jkbennemann\OAuth2\AccessToken
{
    /**
     * @var JWT
     */
    protected $jwt;

    /**
     * @param array $token
     * @throws InvalidAccessToken
     */
    public function __construct(array $token)
    {
        parent::__construct($token);

        if (!isset($token['id_token'])) {
            throw new InvalidAccessToken('id_token doesnot exists inside AccessToken');
        }
    }

    /**
     * @return JWT
     */
    public function getJwt()
    {
        return $this->jwt;
    }

    /**
     * @param JWT $jwt
     */
    public function setJwt(JWT $jwt)
    {
        $payload = $jwt->getPayload();

        if (isset($payload['sub'])) {
            $this->uid = (string) $payload['sub'];
        }

        $this->jwt = $jwt;
    }
}
