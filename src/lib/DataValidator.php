<?php declare(strict_types=1);

namespace Flux\lib;

final class DataValidator {
    
    public function __construct(array $requiredKeys) {
       $this->reqKeys = $requiredKeys;
    }

    public function validateArray(array $data): array {
        $missing = [];
        foreach ($this->reqKeys as $key) {
            if (!array_key_exists($key, $data)) {
                array_push($missing, $key);
            }
        }
        return $missing;
    }

    public function validateAgainstSchema(Schema $schema, Data ...$data): array {
        $invalid = [];
        foreach($data as $row) {
            $arr = $row->asArray();
            if (!$schema->fullfilled($arr)) {
                array_push($invalid, $row);
            }
        }
        return $invalid;
    }
}
