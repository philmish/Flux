<?php declare(strict_types=1);

namespace Flux\lib;

use Exception;

final class DataField  {
    
    private string $type;
    private string $name;
    private mixed $value;

    private function __construct(string $type, string $name, mixed $value) {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
    }
 
    public static function Create(string $type, string $name, mixed $value): self {
        if (gettype($value) != $type) {
            throw new Exception(
                "Failed to create data field. Expected type $type found " . gettype($value)
            );
        }
        return new self($type, $name, $value);
    }

    public static function fromArray(array $data): self {
        $keys = ["name", "type", "value"];
        $missing = [];
        foreach ($keys as $key) {
            if (!array_key_exists($key, $data)) {
                array_push($missing, $key);
            }
        }
        if (count($missing) != 0) {
            $msg = "Failed to initialize DataField. Missing ";
            foreach($missing as $field) {
                $msg .= " $field";
            }
            throw new Exception($msg);
        };
        return DataField::Create($data['type'], $data['name'], $data['value']);
    }

    public function getName(): string {
        return $this->name;
    }

    public function getValue(): mixed {
       return $this->value; 
    }
}

