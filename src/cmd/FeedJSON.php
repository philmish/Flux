<?php declare(strict_types=1);

namespace Flux\cmd;

use Flux\cli\Flags;
use Flux\cli\Command as CliCommand;
use Flux\cli\fs\CollectionLoader;
use Flux\lib\error\CliException;
use Flux\lib\Executor;
use Flux\lib\error\CommandException;
use Flux\lib\error\ExecutorException;
use Flux\lib\error\FSException;

final class FeedJSON extends Command {
    
    private static function flagsOK(Flags $flags): bool {
        return $flags->get("cmd") ==  CliCommand::FeedJSON &&
            $flags->get("file") != "";
    }

    /**
     * @throws CommandException
     */
    public static function execute(Flags $flags, ?Executor $ex = null): void {
        if (!$ex) {
            throw new CommandException("FeedJSON requires a Executor.");
        }
        if (!FeedJSON::flagsOK($flags)) {
            throw new CommandException(
                "Invalid flags provided to ExecScript."
            );
        }
        $src = $flags->get("file");
        try {
            $collection = CollectionLoader::jsonLoad($src);
        } catch (FSException $e) {
            throw new CommandException(
                "Failed to load data for FeedJSON from file.",
                previous:$e,
            );
        }
        try {
            $ex->feed($collection);
        } catch (ExecutorException $e) {
            throw new CommandException(
                "Failed to feed data to DB.",
                previous:$e,
            );
        }
    }

}
