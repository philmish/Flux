<?php declare(strict_types=1);

use Flux\cli\FlagParseMode;
use PHPUnit\Framework\TestCase;
use Flux\cli\Flags;
use Flux\lib\Schema;
use Flux\scan\Scanner;
use Flux\SQLiteExecutor;

/**
 * @covers Flux\scan\Scanner
 *
 * @uses Flux\cli\Flags
 * @uses Flux\SQLiteExecutor
 * @uses Flux\lib\Schema
 *
 */
final class ScannerTest extends TestCase {

    private static string $files;
    private static SQLiteExecutor $db;

    public static function setUpBeforeClass(): void {
        self::$files = __DIR__ . '/../_files/';
        self::$db = SQLiteExecutor::init(":memory:");
        $script =  self::$files . 'sqlite_scriptest.sql';
        $setupData = self::$db->execScript($script);
        if (!$setupData > 0) {
            throw new Exception("Failed to setup Executor for Scanner test.");
        }
    }

    private function TableSchema(): Schema {
        $def = ["table" => "test", "fields" => [
            ["name" => "testStr", "type" => "string"],
            ["name" => "testNum", "type" => "integer"],
            ["name" => "id", "type" => "integer"],
        ]];
        return Schema::fromArray($def);
    }

    public function testBaseTableScan():void {
        $flagFile = self::$files . "flags/base_table_scan_flags.json";
        $flags = Flags::Parse(FlagParseMode::JSON, src:$flagFile);
        $ctx = Scanner::Execute($flags, self::$db);
        $this->assertTrue($ctx->success());
        $arr = $ctx->report();
        $this->assertTrue($arr["table"] == "test");
        $this->assertTrue($arr["length"] == 2);
        $schema = Schema::Create($arr["table"], ...$arr["schema"]);
        $this->assertTrue($schema->isEqualTo($this->TableSchema()));
    }
}

