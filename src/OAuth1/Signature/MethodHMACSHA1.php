<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\OAuth1\Signature;

use Jkbennemann\Provider\Consumer;
use Jkbennemann\OAuth1\Token;
use Jkbennemann\OAuth1\Util;

class MethodHMACSHA1 extends AbstractSignatureMethod
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'HMAC-SHA1';
    }

    /**
     * {@inheritDoc}
     */
    public function buildSignature(string $signatureBase, Consumer $consumer, Token $token)
    {
        $parts = [$consumer->getSecret(), $token->getSecret()];

        $parts = Util::urlencodeRFC3986($parts);
        $key = implode('&', $parts);

        return base64_encode(hash_hmac('sha1', $signatureBase, $key, true));
    }
}
