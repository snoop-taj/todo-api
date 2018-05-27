<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Behat;

use Behat\Behat\Context\Argument\ArgumentResolver as BehatArgumentResolver;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class ArgumentResolver implements BehatArgumentResolver
{
    /** @var ContainerInterface */
    private $container;

    /** @var array */
    private $serviceMap;

    /**
     * @param ContainerInterface $container
     * @param array $serviceMap
     */
    public function __construct(ContainerInterface $container, array $serviceMap)
    {
        $this->container = $container;
        $this->serviceMap = $serviceMap;
    }

    /**
     * Resolves context constructor arguments.
     *
     * @param ReflectionClass $classReflection
     * @param mixed[] $arguments
     *
     * @return mixed[]
     */
    public function resolveArguments(ReflectionClass $classReflection, array $arguments)
    {
        $constructor = $classReflection->getConstructor();

        return $constructor instanceof ReflectionMethod
        ? $this->resolveConstructorArgument($constructor, $arguments)
        : $arguments;
    }

    /**
     * @param ReflectionMethod $constructor
     * @param array $arguments
     * @return array
     * @throws ServiceNotFoundException
     */
    private function resolveConstructorArgument(ReflectionMethod $constructor, array $arguments)
    {
        $constructorParameters = $constructor->getParameters();
        foreach ($constructorParameters as $position => $parameter) {
            $class = $parameter->getClass();
            if ($class instanceof ReflectionClass) {
                $className = $class->getName();
                if (! array_key_exists($className, $this->serviceMap)) {
                    throw new ServiceNotFoundException($className);
                }
                $arguments[$position] = $this->container->get($this->serviceMap[$className]);
            }
        }
        return $arguments;
    }
}
