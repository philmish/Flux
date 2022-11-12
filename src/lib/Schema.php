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
     * Public constructor. Creates a Schema from a table name and one or more DataFields.
     *
     * @param string $table Name of the Table.
     * @param DataField ...$fields One or more DataFields forming the schema.
     *
     * @return Schema
     */
    static public function Create(string $table, DataField ...$fields): self {
        return new self($table, ...$fields);
    }

    /**
     * @throws SchemaException
     *
     * @param array $data
     *
     * @return DataField
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
     * @param array{"table": string, "fields": array<array{"type": string, "name": string}>} $data
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

    /**
     * Retrieves a datafield from the schema deffinition by name.
     *
     * @param string $name Name of the DataField to retrieve.
     *
     * @return ?DataField Returns the DataField with the provided name or null when none is found.
     */
    private function getFieldByName(string $name): ?DataField {
        foreach ($this->fields as $field) {
            if ($field->getName() == $name) {
                return $field;
            }
        }
        return null;
    }

    /**
     * @throws SchemaException
     *
     * Tries to create a new Row of data according to the Schema's DataFields
     *
     * @param array $data Data to be transformed into DataFields.
     *
     * @return array<DataField> Array of DataFields
     */
    public function newRow(array $data): array {
        if (!$this->fullfilled($data)) {
            throw new SchemaException("Data does not fullfill schema.");
        }
        $row = [];
        foreach ($data as $k => $v) {
            $field = $this->getFieldByName($k);
            if (!$field) {
                throw new SchemaException(
                    "Invalid field name $k for Schema of table $this->table."
                );
            }
            try {
                $newField = $field->copyWithValue($v);
            } catch (DataFieldException $e) {
                throw new SchemaException(
                    "Invalid value $v for field $k in table $this->table",
                    previous:$e
                );
            }
            array_push($row, $newField);
        
        }
        return $row;
    }

    /**
     * Takes an array and checks if the keys are present in the Schema and if the type
     * of the Field's Data field matches the value in the provided array.
     * 
     * @param array $data
     * @param bool $exclusiv=true Should the Data only consist of the Field's of this Schema.
     *
     * @return bool Indicates if the provided $data fullfills the Schema.
     */
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

    /**
     * Checks if a field with the given name exists in the Schema.
     *
     * @param string $name Name of the DataField.
     *
     * @return bool Indicator if a DataField with the given name exists..
     */
    public function hasFieldWithName(string $name): bool {
        return in_array($name, $this->fieldNames);
    }

    /**
     * Checks if the provided DataField exists in the Schema.
     *
     * @param DataField $field
     *
     * @return bool Indicator if the DataField exists in the Schema.
     */
    public function hasField(DataField $field): bool {
        foreach($this->fields as $f) {
            if ($field->isSameField($f)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Getter function for the table name of the Schema.
     *
     * @return string Name of the table.
     */
    public function tableName(): string {
        return $this->table;
    }

    /**
     * Getter function for the field names of the Schema.
     *
     * @return array<string> Names of the DataFields of the Schema.
     */
    public function fieldNames(): array {
        return $this->fieldNames;
    }

    /**
     * Getter function for the DataFields of the Schema.
     *
     * @return array<DataField>
     */
    public function getFields(): array {
        return $this->fields;
    }

    /**
     * Compares this Schema with the provided one for equlity.
     *
     * @param Schema $schema
     *
     * @return bool Indicator if both Schemas are equal.
     */
    public function isEqualTo(Schema $schema): bool {
        if ($this->tableName() != $schema->tableName()) {
            return false;
        }
        $fields = $schema->getFields();
        if (count($this->fields) != count($fields)) {
            return false;
        }
        foreach($this->fields as $field) {
            if (!$schema->hasField($field)) {
                return false;
            } 
        }
        return true;
    }
}
