<?php declare(strict_types=1);

namespace Flux\lib;

use Flux\lib\error\DataCollectionException;
use Flux\lib\error\SchemaException;

final class DataCollection {
    private array $collection;
    private Schema $schema;

    private function __construct(Schema $schema, array $data) {
        $this->schema = $schema;
        $this->collection = $data;
    }

    public function data(): array {
        return $this->collection;
    }

    public function containsField(string $name): bool {
        return $this->schema->hasFieldWithName($name);
    }

    static public function fromData(Schema $schema, Data ...$data): DataCollection {
        $items = array();
        array_push($items, ...$data);
        return new self($schema, $items);
    }

    /**
     * @throws DataCollectionException
     */
    static public function fromArrays(Schema $schema, array ...$data): self {
        $collection = [];
        foreach ($data as $item) {
            if (!$schema->fullfilled($item)) {
                throw new DataCollectionException(
                    "Data does not fullfill schema of DataCollection"
                );
            }
            try {
                $row = $schema->newRow($item);
                $asData = Data::create(...$row);
                array_push($collection, $asData);
            } catch (SchemaException $e) {
                throw new DataCollectionException(
                    "Invalid data, cant create item in collection.",
                    previous:$e
                );
            }
        }
        return new self($schema, $collection);
    }

    public function table(): string {
        return $this->schema
                    ->tableName(); 
    }
}
