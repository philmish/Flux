<?php declare(strict_types=1);

namespace Flux\scan;

use Flux\cli\Flags;
use Flux\lib\Executor;

abstract class Scan {
    protected Flags $flags;
    protected ScanContext $ctx;

    protected function __construct(Flags $flags, ScanContext $ctx) {
        $this->flags = $flags;
        $this->ctx = $ctx; 
    }

    abstract static public function Run(Flags $flags, Executor $db): ScanContext;
}
