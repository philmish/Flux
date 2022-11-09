<?php declare(strict_types=1);

use Flux\Lib\DataValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\Lib\DataValidator
 */
final class TestValidator extends TestCase {

    public function testValidation():void {
        $keys = ["hello", "test"];
        $validData = ["hello" => "World", "test" => "test"];
        $invalidData = ["testing" => "invalid", "bye" => "world"];
        $validator = new DataValidator($keys);
        $valid = $validator->validateArray($validData);
        $invalid = $validator->validateArray($invalidData);
        $this->assertTrue(count($valid) == 0);
        $this->assertTrue(count($invalid) == 2);
    }
}

