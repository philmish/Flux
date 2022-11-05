<?php declare(strict_types=1);

namespace Flux\Lib;

final class DataValidator {
    
    public function __construct(array $requiredKeys) {
       $this->reqKeys = $requiredKeys;
    }

    public function run(array $data): bool {
        foreach ($this->reqKeys as $key) {
            if (!array_key_exists($key, $data)) {
                return false;
            }
        }
        return true;
    }
}
