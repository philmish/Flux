<?php declare(strict_types=1);

namespace Flux\lib\error;

use Exception;

final class DataException extends FluxException {
    
    public function __construct(string $msg, ?Exception $previous = null) {
        parent::__construct(msg:$msg, module:"Data", code:11, previous:$previous);
    }
}
