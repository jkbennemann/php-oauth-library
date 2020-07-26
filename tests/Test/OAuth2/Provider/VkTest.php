<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace Test\OAuth2\Provider;

use Psr\Http\Message\ResponseInterface;
use Jkbennemann\OAuth2\AccessToken;

class VkTest extends AbstractProviderTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getProviderClassName()
    {
        return \Jkbennemann\OAuth2\Provider\Vk::class;
    }

    public function testGetIdentitySuccess()
    {
        $mockedHttpClient = $this->mockClientResponse(
            json_encode(
                [
                    'response' => [
                        [
                            'id' => $expectedId = 12321312312312,
                            'first_name' => $expectedFirstname = 'Dmitry',
                            'last_name' => $expectedLastname = 'Patsura',
                            'sex' => 1,
                        ]
                    ]
                ]
            )
        );

        $result = $this->getProvider($mockedHttpClient)->getIdentity(
            new AccessToken(
                [
                    'access_token' => '123456789'
                ]
            )
        );

        parent::assertInstanceOf(\Jkbennemann\Common\Entity\User::class, $result);
        parent::assertSame($expectedId, $result->id);
        parent::assertSame($expectedFirstname, $result->firstname);
        parent::assertSame($expectedLastname, $result->lastname);
        parent::assertSame('female', $result->getSex());
    }

    /**
     * {@inheritDoc}
     */
    protected function getTestResponseForGetIdentity(): ResponseInterface
    {
        return $this->createResponse(
            json_encode([
                'id' => 12345,
            ])
        );
    }
}
