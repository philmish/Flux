<?php declare(strict_types=1);

namespace Flux\Lib;

abstract class DataCollection {
    protected array $collection;

    protected function __construct(array $data) {
        $this->collection = $data;
    }

    abstract static public function fromData(Data $data...): DataCollection;
}
