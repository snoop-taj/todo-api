<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\ApiBundle;


use Etechnologia\Platform\Todo\ApiBundle\DependencyInjection\ApiExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ApiBundle extends Bundle
{
    /** @var string */
    protected $name = 'ApiBundle';

    /**
     * @return string
     */
    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * @return ExtensionInterface
     */
    public function getContainerExtension(): ExtensionInterface
    {
        if ($this->extension === null) {
            $this->extension = new ApiExtension();
        }

        return $this->extension;
    }

    /**
     * @return string
     */
    public function getContainerExtensionClass(): string
    {
        return ApiExtension::class;
    }
}
