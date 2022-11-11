<?php declare(strict_types=1);

namespace Flux\cli;

use Flux\lib\error\CliException;

final class Flags {
    private Command $command;
    private string $table;
    private string $file;
    private string $scanName;

    private function __construct(Command $cmd, string $t = "", string $f = "", string $sn = "") {
       $this->command = $cmd;
       $this->table = $t;
       $this->file = $f;
       $this->scanName = $sn;
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
        return array(
            "sn::", 
        );
    }

    /**
     * @param FlagParseMode $mode Determines where flags are parsed from
     *
     * @return Flags
     */
    static public function Parse(FlagParseMode $mode = FlagParseMode::CLI, string $src = ""): self {
        $opts = match ($mode) {
            FlagParseMode::CLI => getopt(Flags::shortOpts(), Flags::longOpts()),
            FlagParseMode::ENV => getenv(),
            FlagParseMode::JSON => json_decode(file_get_contents($src), true),
            default => getopt(Flags::shortOpts(), Flags::longOpts()),
        };
        if (!$opts) {
            $cmd = Command::Help;
            return new self($cmd);
        }
        $cmd = Command::tryFrom($opts['c']) ?? Command::Help;
        array_key_exists("t", $opts) ? $table = $opts["t"] : $table = "";
        array_key_exists("f", $opts) ? $file = $opts["f"] : $file = "";
        array_key_exists("sn", $opts) ? $scanName = $opts["sn"] : $scanName = "";
        return new self($cmd, $table, $file, $scanName);
    }

    /**
     * @throws CliException
     *
     * @return Command|string
     */
    public function get(string $flag): Command|string {
        $res = match ($flag) {
            "cmd", "c", "command" => $this->command,
            "table", "t" => $this->table,
            "file", "f" => $this->file,
            "scanName", "sn" => $this->scanName, 
            default => throw new CliException("Unknown flag $flag"),
        };
        return $res;
    }

    /**
     * @return array
     */
    public function getArgs(array $keys): array {
        $found = [];
        $missing = [];
        foreach ($keys as $key) {
            try {
                $arg = $this->get($key);
                $found[$key] = $arg;
            } catch (CliException) {
                array_push($missing, $key);
            }
        }
        $result = ["missing" => $missing, "found" => $found];
        return $result;
    }
}
