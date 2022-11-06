<?php declare(strict_types=1);

namespace Flux\lib;

interface Data {
    static public function fromArray(array $data, ?DataValidator $validator = null): Data;
    public function insertQuery(string $table): string;
}
