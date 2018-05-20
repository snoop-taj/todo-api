<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFormatter
{
    public static function json(array $data)
    {
        return new jsonResponse(['data' => $data]);
    }

}
