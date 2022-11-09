<?php declare(strict_types=1);

namespace Flux\lib;

use Flux\lib\error\DataFieldException;
use Flux\lib\error\SchemaException;

final class Schema {

    private string $table;
    private array $fields;
    private array $fieldNames;
    
    /**
     * @throws SchemaException
     */
    private function __construct(string $table, DataField ...$fields) {
        $this->table = $table;
        $this->fields = [];
        $this->fieldNames = [];
        foreach ($fields as $field) {
            if (in_array($field->getName(), $this->fieldNames)) {
                throw new SchemaException(
                    "Fieldname " . $field->getName() . " already exists"
                );
            }
            array_push($this->fields, $field);
            array_push($this->fieldNames, $field->getName());
        }
    }

    /**
     * @throws SchemaException
     */
    private static function genField(array $data): DataField {
        try {
            $field = DataField::defaultField($data);
            return $field;
        } catch (DataFieldException $e) {
            throw new SchemaException(
                "Failed to generate field from data $data",
                previous:$e
            );
        }
     }

    /**
     * @throws SchemaException
     */
    public static function fromArray(array $data): self {
        $keys = ["table", "fields"];
        $validator = new DataValidator($keys);
        $miss = $validator->validateArray($data);
        if (count($miss) > 0) {
            throw new SchemaException(
                "Failed to initialize Schema. Invalid config. $miss missing."
            );
        }
        $fields = $data['fields'];
        $schemaFields = [];
        foreach ($fields as $field) {
            if (!is_array($field)) {
                throw new SchemaException(
                    "Failed to create Schema. Fields must be passed as arrays."
                );
            }
            $newField = Schema::genField($field);
            array_push($schemaFields, $newField);
        }
        return new Schema($data['table'], ...$schemaFields);
    }

    private function getFieldByName(string $name): ?DataField {
        foreach ($this->fields as $field) {
            if ($field->getName() == $name) {
                return $field;
            }
        }
        return null;
    }

    public function fullfilled(array $data, bool $exclusiv = true): bool {
        $lenData = count($data);
        if ($lenData < count($this->fields)) {
            return false;
        } else if ($exclusiv && $lenData > count($this->fields)) {
            return false;
        } else {
            foreach ($data as $field => $value) {
                $field = $this->getFieldByName($field);
                if (!$field) {
                    return false;
                }
                $matches = $field->hasSameType($value);
                if (!$matches) {
                    return false;
                }
            }
            return true;
        }
    }
}
