<?php declare(strict_types=1);

namespace Flux\cmd;

use Flux\cli\Flags;
use Flux\lib\Executor;
use Flux\cli\EscapeColor;

final class Help extends Command {

    private function head(): string {
        return "Flux - SQL quickly";
    }

    private function commands(): string {
        $cmds = "";
        $cmds .= "SQL script execution - execScript\n";
        $cmds .= "Truncating tables - truncate\n";
        return EscapeColor::green($cmds);
    }
    
    static public function execute(Flags $flags, ?Executor $ex = null): void {
        $help = new Help($flags, $ex);
        echo $help->head() . "\n";
        echo "Flux is a CLI utility for interaction with SQL Databases. It provides commands for:\n";
        echo $help->commands();
    }
}
