<?php declare(strict_types=1);

namespace Flux\lib\error;

use Exception;

final class QueryException extends FluxException {
    
    public function __construct(string $msg, ?Exception $previous = null) {
        parent::__construct(msg:$msg, module:"Query", code:6, previous:$previous);
    }
}
