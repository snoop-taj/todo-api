<?php
declare(strict_types=1);

use Etechnologia\Platform\Todo\AppKernel;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/../vendor/autoload.php';

$env = strtolower(getenv('APP_ENV')) ?: 'prod';
$debug = (bool)(strtolower(getenv('APP_ENV')) === 'true');

$kernel = new AppKernel($env, $debug);

if ($kernel->isDebug() == false) {
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();

$request->headers->set('request-time', (new DateTimeImmutable())->format(DATE_ATOM));

$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);