<?php declare(strict_types=1);

namespace Flux\lib\error;

use Exception;

final class DataFieldException extends FluxException {
    
    public function __construct(string $msg, ?Exception $previous = null) {
        parent::__construct(msg:$msg, module:"DataField", code:9, previous:$previous);
    }
}
