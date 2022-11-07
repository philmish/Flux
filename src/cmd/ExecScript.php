<?php declare(strict_types=1);

namespace Flux\cmd;

use Exception;
use Flux\cmd\Command;
use Flux\cli\Command as CliCommand;
use Flux\cli\Flags;
use Flux\lib\Executor;

final class ExecScript extends Command {

    private static function flagsOK(Flags $flags): bool {
        return $flags->get("cmd") ==  CliCommand::ExecScript &&
            $flags->get("file") != "";
    }
    
    public static function execute(Flags $flags, ?Executor $ex = null): void {
        if (!$ex) {
            throw new Exception("ExecScript requires a Executor.");
        }
        if (!ExecScript::flagsOK($flags)) {
            throw new Exception("Invalid flags provided to ExecScript.");
        }
        $src = $flags->get("file");
        $ex->execScript($src);
    }
}
