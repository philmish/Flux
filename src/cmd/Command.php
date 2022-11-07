<?php declare(strict_types=1);

namespace Flux\cmd;

use Flux\cli\Flags;
use Flux\lib\Executor;

abstract class Command {

    protected Flags $flags;
    protected ?Executor $executor;

    protected function __construct(Flags $flags, ?Executor $executor) {
        $this->flags = $flags;
        $this->executor = $executor;
    }

    abstract static public function execute(Flags $flags, ?Executor $ex = null): void;
}
