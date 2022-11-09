<?php declare(strict_types=1);

use Flux\lib\Schema;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\lib\Schema
 */
final class SchemaTest extends TestCase {

    private static Schema $schema;

    public static function setUpBeforeClass(): void {
        $fields = [
            [
                "type" => "str",
                "name" => "testFieldOne"
            ],
            [
                "type" => "int",
                "name" => "testFieldTwo"
            ],
        ];
        $data = [
            "table" => "test",
            "fields" => $fields,
        ];
        self::$schema = Schema::fromArray($data);
    }

    private function provideFullfill(): array {
        return [
            [
                "data" => [
                    "testFieldOne" => "Hello",
                    "testFieldTwo" => 12
                ],
                "passes" => true,
            ],
            [
                "data" => [
                    "testFieldOne" => "WrongType",
                    "testFieldTwo" => "12"
                ],
                "passes" => false,
            ],
        ];
    }

    public function testFullfilled():void {
        $cases = $this->provideFullfill();
        foreach ($cases as $case) {
            $passed = self::$schema->fullfilled($case['data']);
            $this->assertTrue($passed == $case['passes']);
        }
    }
}

