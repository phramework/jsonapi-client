<?php
declare(strict_types=1);

namespace Phramework\JSONAPI\Client\Exceptions;

use Throwable;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 2.6.0
 */
class ConnectException extends NetworkException
{
    public function __construct(Throwable $previous = null)
    {
        \Exception::__construct('Connection refused', 7, $previous);
    }
}
