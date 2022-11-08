<?php declare(strict_types=1);

use Flux\lib\Data;
use Flux\lib\DataField;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\lib\Data
 */
final class DataTest extends TestCase {

    private function provideDatafields(): array {
        $field1 = [
            "type" => "string",
            "name" => "field1",
            "value" => "test",
        ];
        $field2 = [
            "type" => "integer",
            "name" => "field2",
            "value" => 12,
        ];
        return [
            DataField::fromArray($field1),
            DataField::fromArray($field2),
        ];
    }

    public function testCreate():void {
        $data = Data::create(...$this->provideDatafields());
        $table = "test";
        $query = $data->insertQuery($table);

        $expectedQuery = "INSERT INTO test (field1, field2) VALUES (?, ?);";
        $this->assertTrue($query->getQuery() == $expectedQuery);
    }
}

