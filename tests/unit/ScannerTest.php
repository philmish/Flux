<?php declare(strict_types=1);

use Flux\cli\FlagParseMode;
use PHPUnit\Framework\TestCase;
use Flux\cli\Flags;
use Flux\scan\Scanner;
use Flux\SQLiteExecutor;

/**
 * @covers Flux\scan\Scanner
 *
 * @uses Flux\cli\Flags
 * @uses Flux\SQLiteExecutor
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

    public function testBaseTableScan():void {
        $flagFile = self::$files . "flags/base_table_scan_flags.json";
        $flags = Flags::Parse(FlagParseMode::JSON, src:$flagFile);
        $ctx = Scanner::Execute($flags, self::$db);
        $arr = $ctx->report();
        $this->assertTrue($arr["table"] == "test");
        $this->assertTrue($arr["length"] == 2);
    }
}

