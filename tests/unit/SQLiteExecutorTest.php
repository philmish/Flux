<?php declare(strict_types=1);

use Flux\SQLiteExecutor;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\SQLiteExecutor
 */
final class SQLiteExecutorTest extends TestCase {

    private static SQLiteExecutor $db;

    public static function setUpBeforeClass(): void {
        self::$db = SQLiteExecutor::init(":memory:");
    }

    public function testExecScript(): void {
        $script = __DIR__ . '/../_files/sqlite_scriptest.sql';
        $scriptResult = self::$db->execScript($script);
        if (!$scriptResult) {
            throw new Exception("Script execution failed on SQLite test");
        }
        $this->assertTrue($scriptResult == 2);
    }
}

