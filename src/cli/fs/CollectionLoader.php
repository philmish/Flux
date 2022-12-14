<?php declare(strict_types=1);

namespace Flux\cli\fs;

use Flux\lib\DataCollection;
use Flux\lib\DataValidator;
use Flux\lib\error\DataCollectionException;
use Flux\lib\error\FSException;
use Flux\lib\error\SchemaException;
use Flux\lib\Schema;

final class CollectionLoader {
    
    /**
     * @throws FSException
     */
    static public function jsonLoad(string $src): DataCollection {
        $str = file_get_contents($src);
        if (!$str) {
            throw new FSException(
                "Failed to load JSON from $src"
            );
        }
        $data = json_decode($str, true);
        if (!$data) {
            throw new FSException(
                "Failed to decode JSON data from $src"
            );
        }
        $keys = ["table", "fields", "data"];
        $validator = new DataValidator($keys);
        $miss = $validator->validateArray($data);
        if (count($miss) > 0) {
            throw new FSException(
                "Invalid data provided. Missing keys " . implode(", ", $miss)
            );
        }
        try {
            $schema = Schema::fromArray($data);
            $collection = DataCollection::fromArrays($schema, ...$data['data']);
            return $collection;
        } catch (SchemaException $e) {
            throw new FSException(
                "Failed to initialize schema from $src",
                previous:$e
            );
        } catch (DataCollectionException $e) {
            throw new FSException(
                "Failed to create collection from $src",
                previous:$e
            );
        }
    }
}
