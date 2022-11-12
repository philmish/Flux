<?php declare(strict_types=1);

namespace Flux\cmd;

use Flux\cli\Flags;
use Flux\lib\error\CommandException;
use Flux\lib\error\ScanException;
use Flux\lib\Executor;
use Flux\scan\Scanner;

final class Scan extends Command {

    public static function execute(Flags $flags, ?Executor $ex = null): void {
        if (!$ex) {
            throw new CommandException(
                "The Scan command requires an Executor."
            );
        }
        try {
            $ctx = Scanner::Execute($flags, $ex);
        } catch (ScanException $e) {
            throw new CommandException(
                "The Scan command " . $flags->get("sn") . " failed.",
                previous:$e
            );
        }
        //TODO implement command context to pass scan ctx along to CLI App
        $ctx->printReport();
    }
}


