<?php declare(strict_types=1);

namespace Flux\cli;

use Flux\lib\error\FluxException;

final class CommandContext {
    
    private Flags $flags;
    private array $errors = [];
    private bool $success = false;
    private bool $done = false;

    private function __construct(Flags $flags) {
        $this->flags = $flags;
    }

    public static function Start(Flags $flags): self {
        return new self($flags);
    }

    public function isDone(): void {
        $this->done = true;
    }

    public function finished(): bool {
        return $this->done;
    }

    public function succeeded(): void {
        $this->success = true;
        $this->done = true;
    }

    public function failed(): void {
        $this->success = false;
        $this->done = true;
    }

    public function addError(FluxException $e): void {
        array_push($this->errors, $e);
    }
}
