<?php declare(strict_types=1);

namespace Flux\lib\error;

use Exception;

final class CommandException extends FluxException {
    
    public function __construct(string $msg, ?Exception $previous = null) {
        parent::__construct(msg:$msg, module:"Command", code:4, previous:$previous);
    }
}
