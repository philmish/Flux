<?php declare(strict_types=1);

namespace Flux\cmd;

use Flux\cmd\Command;
use Flux\cli\Command as CliCommand;
use Flux\cli\Flags;
use Flux\lib\error\CommandException;
use Flux\lib\error\ExecutorException;
use Flux\lib\Executor;

final class ExecScript extends Command {

    private static function flagsOK(Flags $flags): bool {
        return $flags->get("cmd") ==  CliCommand::ExecScript &&
            $flags->get("file") != "";
    }
    
    /**
     * @throws CommandException
     */
    public static function execute(Flags $flags, ?Executor $ex = null): void {
        if (!$ex) {
            throw new CommandException(
                "ExecScript requires a Executor."
            );
        }
        if (!ExecScript::flagsOK($flags)) {
            throw new CommandException(
                "Invalid flags provided to ExecScript."
            );
        }
        $src = $flags->get("file");
        try {
            $ex->execScript($src);
        } catch (ExecutorException $e) {
            throw new CommandException(
                "Failed to execute Script $src",
                previous:$e
            );
        }
    }
}
