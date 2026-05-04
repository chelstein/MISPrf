<?php

namespace App\Services\Exceptions;

use RuntimeException;

/**
 * Raised by MispClient for any condition that prevents returning a
 * usable response: transport errors, non-2xx statuses, malformed JSON,
 * or MISP-side error envelopes.
 */
class MispClientException extends RuntimeException
{
}
