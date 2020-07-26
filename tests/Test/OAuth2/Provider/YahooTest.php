<?php
/**
 * SocialConnect project
 *
 * @author: Bogdan Popa https://github.com/icex <bogdan@pixelwattstudio.com>
 */


namespace Test\OAuth2\Provider;

use Psr\Http\Message\ResponseInterface;
use Jkbennemann\OAuth2\AccessToken;

class YahooTest extends AbstractProviderTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getProviderClassName()
    {
        return \Jkbennemann\OAuth2\Provider\Yahoo::class;
    }

    /**
     * @throws \Jkbennemann\Provider\Exception\InvalidAccessToken
     */
    public function testParseTokenSuccess()
    {
        $expectedToken = 'XXXXXXXX';
        $expectedUserId = 'ETAPBOZJBRRBKZE6LLUMJYD3JA';

        $accessToken = $this->getProvider()->parseToken(
            json_encode(
                [
                    'access_token' => $expectedToken,
                    // Yahoo uses xoauth_yahoo_guid instead of user_id
                    'xoauth_yahoo_guid' => $expectedUserId
                ]
            )
        );

        parent::assertInstanceOf(AccessToken::class, $accessToken);
        parent::assertSame($expectedToken, $accessToken->getToken());
        parent::assertSame($expectedUserId, $accessToken->getUserId());
    }

    /**
     * {@inheritDoc}
     */
    protected function getTestResponseForGetIdentity(): ResponseInterface
    {
        return $this->createResponse(
            json_encode([
                'profile' => [
                    'id' => 12345,
                ]
            ])
        );
    }
}
