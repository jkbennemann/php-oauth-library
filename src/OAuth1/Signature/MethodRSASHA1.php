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

class MethodRSASHA1 extends AbstractSignatureMethod
{
    /**
     * @var string Path to the private key used for signing
     */
    private $privateKey;

    /**
     * MethodRSASHA1 constructor.
     *
     * @param string $privateKey The path to the private key used for signing
     */
    public function __construct($privateKey)
    {
        if (!is_readable($privateKey)) {
            throw new \InvalidArgumentException('The private key is not readable');
        }

        if (!function_exists('openssl_pkey_get_private')) {
            throw new \InvalidArgumentException('The OpenSSL-Extension seems not to be available. That is necessary to handle RSA-SHA1');
        }

        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'RSA-SHA1';
    }

    /**
     * @param string $signatureBase
     * @param Consumer $consumer
     * @param Token $token
     * @return string
     */
    public function buildSignature(string $signatureBase, Consumer $consumer, Token $token)
    {
        $certificate = openssl_pkey_get_private('file://' . $this->privateKey);
        $privateKeyId = openssl_pkey_get_private($certificate);

        $signature = null;

        openssl_sign($signatureBase, $signature, $privateKeyId, OPENSSL_ALGO_SHA1);
        openssl_free_key($privateKeyId);

        return base64_encode($signature);
    }
}
