<?php declare(strict_types=1);

use Flux\lib\DataField;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\lib\DataField
 */
final class DataFieldTest extends TestCase {

    private function provideCorrectDataField(): array {
        return array(
            "type" => "string",
            "name" => "testField",
            "value" => "Hello World",
        );
    }

    public function testCreate():void {
        $field = DataField::fromArray($this->provideCorrectDataField());
        $this->assertTrue($field->getName() == "testField");
        $this->assertTrue($field->getValue() == "Hello World");
    }

    public function testCreateDefault(): void {
        $field = ["type" => "str", "name" => "testField"];
        $dataField = DataField::defaultField($field);
        $this->assertTrue($dataField->hasSameType("Hello World"));
    }

    public function testIsSameField(): void {
        $testCases = [
            ["matches" => true, "field" => DataField::Create("string", "testStr", "Hello World")],
            ["matches" => false, "field" => DataField::Create("integer", "testStr", 12)],
        ];
        $field = DataField::Create("string", "testStr", "Hello World");
        foreach ($testCases as $case) {
            $this->assertTrue($case["matches"] == $field->isSameField($case["field"]));
        }
    }
}


