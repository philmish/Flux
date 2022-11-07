<?php declare(strict_types=1);

namespace Flux\cli;

use Exception;
use Flux\lib\Executor;
use Flux\SQLiteExecutor;

final class Configuration {
    private Driver $driver;
    private string $dsn;
    
    private function __construct(Driver $driver, string $dsn) {
        $this->driver = $driver;
        $this->dsn = $dsn; 
    }

    private static function envVars(): array {
        return array(
            "driver" => "FLUX_DRIVER",
            "dsn" => "FLUX_DSN",
        );
    }

    public static function fromEnv(): self {
        $vars = Configuration::envVars();
        $dr = getenv($vars['driver']);
        $dsn = getenv($vars['dsn']);
        if (!$dr || !$dsn) {
            throw new Exception("You need to provide a driver and a DSN.");
        }
        $driver = Driver::from($dr);
        return new self($driver, $dsn);
    }

    public function DB(): Executor {
        // TODO implement missing Drivers
        $ex = match ($this->driver) {
            Driver::SQLite => SQLiteExecutor::init($this->dsn),
            default => throw new Exception("Unknown driver " . $this->driver->value),
        };
        return $ex;
    }
}
