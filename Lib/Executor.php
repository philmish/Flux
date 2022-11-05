<?php declare(strict_types=1);

namespace Flux\Lib;

use PDO;
use Exception;

abstract class Executor {
    protected PDO $db;

    abstract public function feed(DataCollection $data, string $table);

    public function truncate(string $table): void {
        $result = $this->db->prepare("TRUNCATE TABLE ?", [$table]);
        if (!$result) {
            throw new Exception("Failed to truncate $table");
        }
    }

    public function execScript(string $source): void { 
        $sql = file_get_contents($source);
        if (!$sql) {
            //TODO Implement custom exceptions
            throw new Exception("Failed to read script.");
        }
        $result = $this->db->exec($sql);
        if (!$result) {
            throw new Exception("Failed to execute script.");
        }
    }
}
