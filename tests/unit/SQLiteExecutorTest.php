<?php declare(strict_types=1);

use Flux\cli\fs\CollectionLoader;
use Flux\SQLiteExecutor;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\SQLiteExecutor
 */
final class SQLiteExecutorTest extends TestCase {

    private static SQLiteExecutor $db;
    private static string $files;

    public static function setUpBeforeClass(): void {
        self::$files = __DIR__ . '/../_files/';
        self::$db = SQLiteExecutor::init(":memory:");
    }

    public function testExecScript(): void {
        $script =  self::$files . 'sqlite_scriptest.sql';
        $scriptResult = self::$db->execScript($script);
        if (!$scriptResult) {
            throw new Exception("Script execution failed on SQLite test");
        }
        $this->assertTrue($scriptResult == 2);
    }

    /**
     * @depends testExecScript
     */
    public function testFeedJsonData(): void {
        $json = self::$files . "feed_test.json";
        $data = CollectionLoader::jsonLoad($json);
        $this->assertTrue($data->table() == "test");
        self::$db->feed($data);
    }
}

