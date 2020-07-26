<?php
/**
 * SocialConnect project
 * @author: Andreas Heigl https://github.com/heiglandreas <andreas@heigl.org>
 */

namespace Test\OAuth2\Provider;

use Psr\Http\Message\ResponseInterface;
use Jkbennemann\OAuth2\AccessToken;
use Jkbennemann\OAuth2\Provider\Meetup;
use Jkbennemann\Provider\Session\SessionInterface;

class MeetupTest extends AbstractProviderTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getProviderClassName()
    {
        return \v\OAuth2\Provider\Meetup::class;
    }

    /** @covers \Jkbennemann\OAuth2\Provider\Meetup::parseToken */
    public function testParsingToken()
    {
        $expires = time() + 200;
        $body = json_encode([
            'access_token' => 'foo',
            'user_id'      => 'bar',
            'expires'      => $expires,
        ]);

        $meetup = new Meetup(
            $this->getHttpStackMock(),
            self::createMock(SessionInterface::class),
            $this->getProviderConfiguration()
        );

        $token = $meetup->parseToken($body);

        self::assertInstanceOf(AccessToken::class, $token);
        self::assertAttributeEquals('foo', 'token', $token);
        self::assertAttributeEquals($expires, 'expires', $token);
        self::assertAttributeEquals('bar', 'uid', $token);
    }

    /**
     * @expectedException \Jkbennemann\Provider\Exception\InvalidAccessToken
     * @covers \Jkbennemann\OAuth2\Provider\Meetup::parseToken
     */
    public function testParsingTokenFailsWithInvalidBody()
    {
        $meetup = new Meetup(
            $this->getHttpStackMock(),
            self::createMock(SessionInterface::class),
            $this->getProviderConfiguration()
        );

        $meetup->parseToken(json_encode(false));
    }

    /**
     * {@inheritDoc}
     */
    protected function getTestResponseForGetIdentity(): ResponseInterface
    {
        return $this->createResponse(
            json_encode([
                'id' => 12345,
                'name' => 'Dmitry',
                'gender' => 'sex',
                'photo' => [
                    'photo_link' => 'http://test.com/1.jpg'
                ],
            ])
        );
    }
}
