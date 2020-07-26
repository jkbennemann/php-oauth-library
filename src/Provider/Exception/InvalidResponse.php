<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\Provider\Exception;

use Psr\Http\Message\ResponseInterface;

class InvalidResponse extends \Jkbennemann\Common\Exception
{
    /**
     * @var ResponseInterface|null
     */
    protected $response;

    /**
     * @param string $message
     * @param ResponseInterface|null $response
     */
    public function __construct($message = 'API bad response', ResponseInterface $response = null)
    {
        parent::__construct($message);

        $this->response = $response;
    }

    /**
     * Get response data.
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}
