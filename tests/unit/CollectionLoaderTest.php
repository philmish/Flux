<?php declare(strict_types=1);

use Flux\cli\fs\CollectionLoader;
use PHPUnit\Framework\TestCase;

/**
 * @covers Flux\cli\fs\CollectionLoader
 */
final class CollectionLoaderTest extends TestCase {

    private static string $files;

    public static function setUpBeforeClass(): void {
        self::$files = __DIR__ . '/../_files/';
    }

    public function testJsonLoading():void {
        $src = self::$files . "feed_test.json";
        $collection = CollectionLoader::jsonLoad($src); 
        $this->assertTrue($collection->containsField("testStr"));
        $this->assertTrue($collection->containsField("testNum"));
        
    }
}


