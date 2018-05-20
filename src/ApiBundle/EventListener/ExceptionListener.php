<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\ApiBundle\EventListener;

use Exception;
use Etechnologia\Platform\Todo\Core\ErrorCodeMapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionListener
{
    private const DEFAULT_ERROR_MESSAGE = 'Unknown error';

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $e = $event->getException();
        [$code, $msg, $className] = self::extractCodeMsgClass($e);
        $this->logException($code, $msg, $className);
        $event->setResponse($this->responseFromException($code, $msg, $className));
    }

    /**
     * @param int $code
     * @param string $msg
     * @param string $className
     */
    private function logException(int $code, string $msg, string $className): void
    {
        if (ErrorCodeMapper::isKnownError($className)) {
            $this->logger->error(sprintf('[%d] - %s', $code, $msg), [$className]);
        } else {
            $this->logger->critical(sprintf('[%d] - %s', $code, $msg), [$className]);
        }
    }

    /**
     * @param int $code
     * @param string $msg
     * @param string $className
     * @return JsonResponse
     */
    private function responseFromException(int $code, string $msg, string $className): JsonResponse
    {
        $responseData = [
            'error' => [
                'code'    => $code,
                'message' => $msg
            ]
        ];

        return new JsonResponse($responseData, ErrorCodeMapper::getHttpCode($className));
    }

    /**
     * @param Exception $e
     * @return array
     */
    private static function extractCodeMsgClass(Exception $e): array
    {
        return [
            ErrorCodeMapper::getErrorCode(get_class($e)),
            strlen($e->getMessage()) > 0 ? $e->getMessage() : self::DEFAULT_ERROR_MESSAGE,
            get_class($e)
        ];
    }
}
