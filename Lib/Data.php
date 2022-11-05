<?php declare(strict_types=1);

namespace Flux\Lib;

interface Data {
    static public function fromArray(array $data, ?DataValidator $validator = null): Data;
    public function insertQuery(string $table): string;
}
