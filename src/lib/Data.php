<?php declare(strict_types=1);

namespace Flux\lib;

use Flux\lib\error\DataException;

final class Data {

    private array $fields;
    
    private function __construct(array $fields) {
       $this->fields = $fields; 
    }

    /**
     * @throws DataException
     */
    static public function create(DataField ...$fields): self {
        $names = [];
        $dataFields = [];
        foreach($fields as $field) {
            if (in_array($field->getName(), $names)) {
                throw new DataException(
                    "Datafield with name " . $field->getName() . " already exists."
                );
            }
            array_push($dataFields, $field);
        }
        return new self($dataFields);
    }

    public function insertQuery(string $table): Query {
        $queryBegin = "INSERT INTO $table (";
        $queryEnd = ") VALUES (";
        $args = [];

        foreach ($this->fields as $field) {
            $queryBegin .= $field->getName() . ", ";
            $queryEnd .= "?, ";
            array_push($args, $field->getValue());
        }

        $queryBegin = rtrim($queryBegin, ", ");
        $queryEnd = rtrim($queryEnd, ", ");
        $queryEnd .= ");";

        $queryStr = $queryBegin . $queryEnd;

        return Query::Insert($queryStr, $args);
    }
}
