<?php declare(strict_types=1);

namespace Flux\lib;

use PDO;
use Exception;
use PDOException;

abstract class Executor {
    protected PDO $db;

    protected function __construct(PDO $db) {
        $this->db = $db;
    }

    abstract static public function init(string $dsn): Executor;

    public function feed(DataCollection $data, string $table): void {
        try {
            $tr = $this->db->beginTransaction();
        } catch (PDOException $e) {
            throw new Exception("Failed to start Db transaction.", previous:$e);
        }
        if (!$tr) {
            throw new Exception("No transaction was initialized.");
        }
        foreach ($data->data() as $item) {
            if (!$item instanceof Data) {
                $this->db->rollBack();
                throw new Exception("Unexpected data type. Rolling back previous inserts.");
            }
            try {
                $stmt = $this->db->prepare($item->insertQuery($table));
                $stmt->execute();
            } catch (PDOException $e) {
                $this->db->rollBack();
                throw new Exception(
                    "Encounterd error executing insert. Rolling back previous inserts.", 
                    previous:$e
                );
            }
        }
        $this->db->commit();
    }

    public function truncate(string $table): void {
        $result = $this->db->prepare("TRUNCATE TABLE $table")->execute();
        if (!$result) {
            throw new Exception("Failed to truncate $table");
        }
    }

    public function execScript(string $source): int { 
        $sql = file_get_contents($source);
        if (!$sql) {
            //TODO Implement custom exceptions
            throw new Exception("Failed to read script.");
        }
        $result = $this->db->exec($sql);
        if (!$result) {
            throw new Exception("Script execution failed.");
        }
        return $result;
    }
}
