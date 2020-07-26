<?php
/**
 * SocialConnect project
 * @author: Andreas Heigl https://github.com/heiglandreas <andreas@heigl.org>
 */

namespace Test\OAuth1\Signature;

use Jkbennemann\OAuth1\Signature\MethodHMACSHA1;
use Jkbennemann\OAuth1\Token;
use Jkbennemann\Provider\Consumer;
use Test\AbstractTestCase;

class MethodHMACSHA1Test extends AbstractTestCase
{
    public function testCreatingSignatureWorks()
    {
        $signer = new MethodHMACSHA1();

        $consumer = $this->getMockBuilder(Consumer::class)->disableOriginalConstructor()->getMock();
        $consumer->method('getSecret')->willReturn('consumerSecret');

        $token = $this->getMockBuilder(Token::class)->disableOriginalConstructor()->getMock();
        $token->method('getSecret')->willReturn('tokenSecret');

        $signature = $signer->buildSignature('signature', $consumer, $token);

        $this->assertEquals('xG1+MDlpKTVe8iHHtc1fLRM2U1s=', $signature);
    }
}
