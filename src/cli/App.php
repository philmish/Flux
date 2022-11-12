<?php declare(strict_types=1);

namespace Flux\cli;

use Flux\cmd\ExecScript;
use Flux\cmd\FeedJSON;
use Flux\cmd\Help;
use Flux\cmd\Scan;
use Flux\lib\error\CliException;
use Flux\lib\error\CommandException;

final class App {

    private Flags $flags;
    private Configuration $conf;
    
    private function __construct(Flags $flags, Configuration $conf) {
       $this->flags = $flags;
       $this->conf = $conf;
    }

    /**
     *
     * @throws CommandException
     * @throws CliException
     *
     */
    private function executeCommand(): void {
        $cmd = $this->flags->get("cmd");
        switch ($cmd) {
        case Command::Help:
            Help::execute($this->flags);
            return;
        case Command::ExecScript:
            ExecScript::execute(
                $this->flags,
                $this->conf->DB()
            );
            return;
        case Command::FeedJSON:
            FeedJSON::execute(
                $this->flags,
                $this->conf->DB()
            );
            return;
        case Command::Scan:
            Scan::execute(
                $this->flags,
                $this->conf->DB(),
            );
            return;
        default:
            Help::execute($this->flags);
            return;
        };
    }

    /**
     * Constructor function for the App class used in the Flux CLI.
     * It parses the program flags from the provided command line arguments
     * and the configuration from the following environment variables:
     *
     * - FLUX_DSN The Data Source Name to connect to the Database.
     * - FLUX_DRIVER The Database driver to use (sqlite, mysql, mariadb)
     */
    public static function Run(): void {
        $flags = Flags::Parse();
        $conf = Configuration::fromEnv();
        $app = new self($flags, $conf);
        $app->executeCommand();
    }

    /**
     * A alternative constructor function for the App class.
     * Can be used to script your Flux workflows by loading the
     * flags from a config.json instead of providing them over the CLI.
     *
     * @throws CommandException
     * @throws CliException
     *
     * @param string $src Path the config file to use for parsing flags.
     *
     */
    public static function RunFromJSON(string $src): void {
        $flags = Flags::Parse(mode:FlagParseMode::JSON, src:$src);
        $conf = Configuration::fromEnv();
        $app = new self($flags, $conf);
        $app->executeCommand();
    }
}
