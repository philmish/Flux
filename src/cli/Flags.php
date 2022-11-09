<?php declare(strict_types=1);

namespace Flux\cli;

use Flux\lib\error\CliException;

final class Flags {
    private Command $command;
    private string $table;
    private string $file;

    private function __construct(Command $cmd, string $t = "", string $f = "") {
       $this->command = $cmd;
       $this->table = $t;
       $this->file = $f;
    }

    static private function shortOpts(): string {
        $opts = "";
        // Required
        $opts .= "c:";
        // Optional
        $opts .= "t::";
        $opts .= "f::";

        return $opts;
    }

    static private function longOpts(): array {
        return array();
    }

    static public function Parse(): self {
        $opts = getopt(Flags::shortOpts(), Flags::longOpts());
        if (!$opts) {
            $cmd = Command::Help;
            return new self($cmd);
        }
        $cmd = Command::tryFrom($opts['c']) ?? Command::Help;
        array_key_exists("t", $opts) ? $table = $opts["t"] : $table = "";
        array_key_exists("f", $opts) ? $file = $opts["f"] : $file = "";
        return new self($cmd, $table, $file);
    }

    /**
     * @throws CliException
     */
    public function get(string $flag): Command|string {
        $res = match ($flag) {
            "cmd", "c", "command" => $this->command,
            "table", "t" => $this->table,
            "file", "f" => $this->file,
            default => throw new CliException("Unknown flag $flag"),
        };
        return $res;
    }
}
