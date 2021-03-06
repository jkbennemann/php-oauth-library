<?php
/**
 * SocialConnect project
 * @author: Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */
declare(strict_types=1);

namespace Jkbennemann\Auth;

use Jkbennemann\Provider\Exception\InvalidProviderConfiguration;
use Jkbennemann\Common\HttpStack;
use Jkbennemann\Provider\Session\SessionInterface;

class Service
{
    /**
     * @var HttpStack
     */
    protected $httpStack;

    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @param HttpStack $httpStack
     * @param SessionInterface $session
     * @param array $config
     * @param FactoryInterface|null $factory
     * @internal param $storage
     */
    public function __construct(HttpStack $httpStack, SessionInterface $session, array $config, FactoryInterface $factory = null)
    {
        $this->httpStack = $httpStack;
        $this->session = $session;
        $this->config = $config;

        $this->factory = is_null($factory) ? new CollectionFactory() : $factory;
    }

    /**
     * @param string $name
     * @return array
     * @throws InvalidProviderConfiguration
     */
    public function getProviderConfiguration(string $name)
    {
        if (isset($this->config['provider'][$name])) {
            return $this->config['provider'][$name];
        }

        throw new InvalidProviderConfiguration(
            'Please setup configuration for ' . ucfirst($name) . ' provider'
        );
    }

    /**
     * Check that provider exists by $name
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name)
    {
        return $this->factory->has($name);
    }

    /**
     * Get provider class by $name
     *
     * @param string $name
     * @return \Jkbennemann\Provider\AbstractBaseProvider
     * @throws InvalidProviderConfiguration
     */
    public function getProvider(string $name)
    {
        return $this->factory->factory($name, $this->getProviderConfiguration($name), $this);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return HttpStack
     */
    public function getHttpStack(): HttpStack
    {
        return $this->httpStack;
    }

    /**
     * Set the configuration either in general or for a specified $provider
     *
     * @param array $config
     * @param null $provider
     */
    public function setConfig(array $config, $provider = null)
    {
        if ($provider === null) {
            $this->config = $config;
            return;
        }

        $this->config[strtolower($provider)] = $config;
    }
}
