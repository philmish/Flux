<?php declare(strict_types=1);

namespace Flux\cmd;

use Exception;
use Flux\cmd\Command;
use Flux\cli\Flags;
use Flux\cli\Command as CliCommand;
use Flux\lib\error\CommandException;
use Flux\lib\Executor;

final class Truncate extends Command {
    
    private static function flagsOK(Flags $flags): bool {
        return $flags->get("cmd") ==  CliCommand::Truncate &&
            $flags->get("table") != "";
    }

    /**
     * @throws CommandException
     */
    public static function execute(Flags $flags, ?Executor $ex = null): void {
        if (!$ex) {
            throw new CommandException("Truncate call misses Executor");
        }
        if (!Truncate::flagsOK($flags)) {
            throw new CommandException("Invalid Flags for truncate command.");
        }

        $table = $flags->get("table");
        $ex->truncate($table);
    }
}


