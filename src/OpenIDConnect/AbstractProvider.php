<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OpenIDConnect;

use Jkbennemann\Common\Entity\User;
use SocialConnect\JWX\DecodeOptions;
use SocialConnect\JWX\JWKSet;
use SocialConnect\JWX\JWT;
use Jkbennemann\Provider\AccessTokenInterface;
use Jkbennemann\Provider\Exception\InvalidAccessToken;
use Jkbennemann\Provider\Exception\InvalidResponse;

abstract class AbstractProvider extends \Jkbennemann\OAuth2\AbstractProvider
{
    /**
     * @return array
     * @throws InvalidResponse
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function discover(): array
    {
        return $this->hydrateResponse(
            $this->executeRequest(
                $this->httpStack->createRequest('GET', $this->getOpenIdUrl())
            )
        );
    }

    /**
     * @return JWKSet
     * @throws InvalidResponse
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function getJWKSet(): JWKSet
    {
        $spec = $this->discover();

        if (!isset($spec['jwks_uri'])) {
            throw new \RuntimeException('Unknown jwks_uri inside OpenIDConnect specification');
        }

        $response = $this->executeRequest(
            $this->httpStack->createRequest('GET', $spec['jwks_uri'])
        );

        $result = $this->hydrateResponse($response);

        if (!isset($result['keys'])) {
            throw new InvalidResponse(
                'API response without "keys" key inside JSON',
                $response
            );
        }

        return new JWKSet($result);
    }

    /**
     * @return string
     */
    abstract public function getOpenIdUrl();

    /**
     * {@inheritdoc}
     */
    public function getAuthUrlParameters(): array
    {
        $parameters = parent::getAuthUrlParameters();

        // special parameters only required for OpenIDConnect
        $parameters['client_id'] = $this->consumer->getKey();
        $parameters['redirect_uri'] = $this->getRedirectUrl();
        $parameters['response_type'] = 'code';
        // Optional field...
        //$parameters['response_mode'] = 'form_post';
        $parameters['scope'] = 'openid';

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function parseToken(string $body)
    {
        if (empty($body)) {
            throw new InvalidAccessToken('Provider response with empty body');
        }

        $result = json_decode($body, true);
        if ($result) {
            $token = new AccessToken($result);
            $token->setJwt(
                JWT::decode($result['id_token'], $this->getJWKSet(), new DecodeOptions())
            );

            return $token;
        }

        throw new InvalidAccessToken('Provider response with not valid JSON');
    }

    /**
     * Extract data from JWT->payload and create User
     * In some providers it's possible to get email/emailVerified or another data from JWT
     * And due this fact it's not needed to do server request to get identity
     *
     * @param AccessTokenInterface $accessToken
     * @return User
     */
    abstract public function extractIdentity(AccessTokenInterface $accessToken);

    /**
     * {@inheritDoc}
     */
    public function createAccessToken(array $information)
    {
        $token = new AccessToken($information);
        $token->setJwt(
            JWT::decode($information['id_token'], $this->getJWKSet(), new DecodeOptions())
        );

        return $token;
    }
}
