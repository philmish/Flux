<?php declare(strict_types=1);

namespace Flux\lib;

use Flux\lib\error\DataFieldException;

final class DataField  {
    
    private string $type;
    private string $name;
    private mixed $value;
    private string $nativeType;

    private function __construct(string $type, string $name, mixed $value, string $nativeType) {
        $this->type = $type;
        $this->name = $name;
        $this->value = $value;
        $this->nativeType = $nativeType;
    }
 
    /**
     * @throws DataFieldException
     */
    public static function Create(string $type, string $name, mixed $value, string $nativeType = ""): self {
        if (gettype($value) != $type) {
            throw new DataFieldException(
                "Failed to create data field. Expected type $type found " . gettype($value)
            );
        }
        return new self($type, $name, $value, $nativeType);
    }

    /**
     * @throws DataFieldException
     *
     * @param array{"name": string, "type": string, "value": mixed} $data Data to use for the creation of the field.
     *
     * @return DataField
     */
    public static function fromArray(array $data): self {
        $keys = ["name", "type", "value"];
        $validator = new DataValidator($keys);
        $missing = $validator->validateArray($data);
        if (count($missing) != 0) {
            $msg = "Failed to initialize DataField. Missing ";
            foreach($missing as $field) {
                $msg .= " $field";
            }
            throw new DataFieldException($msg);
        };
        array_key_exists("nativeType", $data) ?: $data['nativeType'] = "";
        return DataField::Create($data['type'], $data['name'], $data['value'], $data['nativeType']);
    }

    static public function fromKeyValue(mixed $key, mixed $value): self {
        $type = gettype($value);
        return DataField::Create($type, $key, $value);
    }

    /**
     * @throws DataFieldException
     *
     * @param array{"type": string, "name": string} Type and Name of the field.
     *
     * @return DataField
     */
    public static function defaultField(array $data): self {
        $keys = ["type", "name"];
        $validator = new DataValidator($keys);
        $missing = $validator->validateArray($data);
        if (count($missing) != 0) {
            $msg = "Failed to initialize default DataField. Missing ";
            foreach($missing as $field) {
                $msg .= " $field";
            }
            throw new DataFieldException($msg);
        };
        switch ($data['type']) {
        case "int":
        case "integer":
            $data["type"] = "integer";
            $data['value'] = 0;
            break;
        case "str":
        case "string":
            $data["type"] = "string";
            $data['value'] = "";
            break;
        case "bool":
        case "boolean":
            $data["type"] = "bool";
            $data['value'];
            break;
        case "array":
        case "list":
        case "json":
            $data["type"] = "array";
            $data['value'] = array();
            break;
        default:
            throw new DataFieldException(
                "Failed to initialize default field. Unsupported type " . $data["type"]
            );
        }
        $data['nativeType'] = "";
        return DataField::fromArray($data);
    }

    public function getName(): string {
        return $this->name;
    }

    public function type(): string {
        return $this->type;
    }

    public function getValue(): mixed {
       return $this->value; 
    }

    public function getNativeType(): string {
        return $this->nativeType;
    }

    public function hasSameType(mixed $data): bool {
        return gettype($data) == $this->type;
    }

    public function hasSameNativeType(DataField $field): bool {
        return $this->nativeType == $field->getNativeType();
    }

    public function isSameField(DataField $field): bool {
        return $this->name == $field->getName() && $this->type == $field->type();
    }

    public function isSameFieldAndValue(DataField $field): bool {
        return $this->isSameField($field) && $this->value == $field->getValue();
    }

    public function copyWithValue(mixed $value): DataField {
        if (!$this->hasSameType($value)) {
            throw new DataFieldException(
                "Invalid data type " . gettype($value) . " for field of type $this->type"
            );
        }
        $field = [
            "type" => $this->type,
            "name" => $this->name,
            "value" => $value,
        ];
        return DataField::fromArray($field);
    }
}

