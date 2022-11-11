<?php declare(strict_types=1);

namespace Flux\scan;

use Exception;

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

    abstract static public function Create(ScanName $scan): ScanContext;
}
