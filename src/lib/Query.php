<?php declare(strict_types=1);

namespace Flux\lib;

final class Query {
    
    private string $query;
    private array $args;

    private function __construct(string $query, array $args) {
        $this->query = $query;
        $this->args = $args; 
    }

    static public function Insert(string $query, array $args = []): self {
        return new self($query, $args);
    }

    public function getQuery(): string {
        return $this->query;
    }

    public function getArgs(): array {
        return $this->args;
    }
}
