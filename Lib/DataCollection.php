<?php declare(strict_types=1);

namespace Flux\Lib;

abstract class DataCollection {
    protected array $collection;

    protected function __construct(array $data) {
        $this->collection = $data;
    }

    public function data(): array {
        return $this->collection;
    }

    static public function fromData(Data ...$data): DataCollection {
        $items = array();
        array_push($items, $data);
        return new self($items);
    }

    // Creates a array of data from one or multiple arrays of data. It uses the datafields to
    // validate the data in each of the arrays in $data.
    abstract static public function fromArrays(array ...$data, array $datafields): DataCollection;
}
