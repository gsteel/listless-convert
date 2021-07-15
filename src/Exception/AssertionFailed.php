<?php

declare(strict_types=1);

namespace GSteel\Listless\Convert\Exception;

use InvalidArgumentException;

final class AssertionFailed extends InvalidArgumentException implements Exception
{
}
