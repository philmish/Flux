<?php declare(strict_types=1);

use Flux\SQLiteExecutor;
use PHPUnit\Framework\TestCase;

final class SQLiteExecutorTest extends TestCase {

    private static SQLiteExecutor $db;

    public static function setUpBeforeClass(): void {
        self::$db = SQLiteExecutor::init(":memory:");
    }

    public function testExecScript(): void {
        $script = __DIR__ . '/../_files/sqlite_scriptest.sql';
        self::$db->execScript($script);
        self::$db->truncate("test");
    }
}

