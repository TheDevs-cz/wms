<?php

declare(strict_types=1);

namespace TheDevs\WMS\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(Response::HTTP_FORBIDDEN)]
final class AccessDenied extends \Exception
{
}
