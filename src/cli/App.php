<?php declare(strict_types=1);

namespace Flux\cli;

use Flux\cmd\ExecScript;
use Flux\cmd\Help;

final class App {

    private Flags $flags;
    private Configuration $conf;
    
    private function __construct(Flags $flags, Configuration $conf) {
       $this->flags = $flags;
       $this->conf = $conf;
    }

    private function executeCommand(): void {
        $cmd = $this->flags->get("cmd");
        switch ($cmd) {
        case Command::Help:
            Help::execute($this->flags);
            return;
        case Command::ExecScript:
            ExecScript::execute($this->flags, $this->conf->DB());
            return;
        default:
            Help::execute($this->flags);
            return;
        };
    }

    public static function Run(): void {
        $flags = Flags::Parse();
        $conf = Configuration::fromEnv();
        $app = new self($flags, $conf);
        $app->executeCommand();
    }
}
