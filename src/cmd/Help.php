<?php declare(strict_types=1);

namespace Flux\cmd;

use Flux\cli\Flags;
use Flux\lib\Executor;

final class Help extends Command {
    
    static public function execute(Flags $flags, ?Executor $ex = null): void {
       echo "WIP Help command.";
    }
}
