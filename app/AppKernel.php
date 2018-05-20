<?php

namespace Etechnologia\Platform\Todo;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use EightPoints\Bundle\GuzzleBundle\GuzzleBundle;
use Etechnologia\Platform\Todo\ApiBundle\ApiBundle;
use Exception;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public const ENV_LOCAL      = 'local';
    public const ENV_STAGING    = 'staging';
    public const ENV_PRODUCTION = 'prod';
    public const ENV_TEST       = 'test';

    public const ENVS = [
        self::ENV_LOCAL,
        self::ENV_STAGING,
        self::ENV_PRODUCTION,
        self::ENV_TEST,
    ];

    /**
     * @param string $env
     * @return bool
     */
    public static function isValidEnv(string $env): bool
    {
        return in_array($env, self::ENVS, true);
    }

    /**
     * @return array
     */
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new MonologBundle(),


            new NelmioApiDocBundle(),
            new GuzzleBundle(),
            new TwigBundle(),
            new ApiBundle(),
        ];
    }

    /**
     * @param LoaderInterface $loader
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/config.yml');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'et_ms_to_do_list';
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return __DIR__;
    }

    /**
     * @return string
     */
    public function getLogDir(): string
    {
        return $this->rootDir . '/../var/log/' . $this->environment;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->rootDir . '/../var/cache/' . $this->environment;
    }
}
