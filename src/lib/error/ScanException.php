<?php declare(strict_types=1);

namespace Flux\lib\error;

use Exception;

final class ScanException extends FluxException {
    
    public function __construct(string $msg, ?Exception $previous = null) {
        parent::__construct(msg:$msg, module:"Scan", code:15, previous:$previous);
    }
}
