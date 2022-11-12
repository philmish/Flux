<?php declare(strict_types=1);

namespace Flux\scan;

use Exception;
use Flux\cli\EscapeColor;

abstract class ScanContext {
    protected ScanName $scanName;
    protected array $report;
    protected bool $done = false;
    protected bool $success = false;
    protected array $errors = [];

    protected function __construct(array $report, ScanName $scanName) {
        $this->scanName = $scanName;
        $this->report = $report;
    }

    protected static function UnknownScan(): self {
        $ctx = new self([], ScanName::Unknown);
        $ctx->failed();
        return $ctx;
    }

    public function set(string $key, mixed $value): void {
        $this->report[$key] = $value;
    }

    public function pushErr(Exception $err): void {
        array_push($this->errors, $err);
    }

    public function isDone(): void {
        $this->done = true;
    }

    public function done(): bool {
        return $this->done;
    }

    public function failed(): void {
        $this->success = false;
        $this->isDone();
    }

    public function succeeded(): void {
        $this->success = true;
        $this->isDone();
    }

    public function report(): array {
        return $this->report;
    }

    public function printReport(): void {
        $msg = "Scan: " . $this->scanName->value . "\n";
        $status = $this->success ? EscapeColor::green("Status: Success") : EscapeColor::boldRed("Status: Failed");
        $msg .= $status . "\n";
        foreach($this->report() as $k => $v) {
            $val = match(gettype($v)) {
                "integer" => "$v",
                "array" => implode(", ", $v),
                default => $v,
            };
            $msg .= $k . ": " . $val;
        }
        $msg .= "Errors: \n";
        foreach($this->errors as $err) {
            $msg .= "\t" . $err->getMessage() . "\n";
        }
        echo $msg;
    }

    abstract static public function Create(ScanName $scan): ScanContext;
}
