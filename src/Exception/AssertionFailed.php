<?php

declare(strict_types=1);

namespace ListInterop\ConvertKit\Exception;

use InvalidArgumentException;

final class AssertionFailed extends InvalidArgumentException implements Exception
{
}
