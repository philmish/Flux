<?php declare(strict_types=1);

namespace Flux\lib;

use PDO;
use Exception;
use Flux\lib\error\ExecutorException;
use PDOException;

abstract class Executor {
    protected PDO $db;

    protected function __construct(PDO $db) {
        $this->db = $db;
    }

    abstract static public function init(string $dsn): Executor;

    /**
     * @throws ExecutorException
     */
    public function feed(DataCollection $data, string $table): void {
        try {
            $tr = $this->db->beginTransaction();
        } catch (PDOException $e) {
            throw new ExecutorException("Failed to start Db transaction.", previous:$e);
        }
        if (!$tr) {
            throw new ExecutorException("No transaction was initialized.");
        }
        foreach ($data->data() as $item) {
            if (!$item instanceof Data) {
                $this->db->rollBack();
                throw new ExecutorException("Unexpected data type. Rolling back previous inserts.");
            }
            try {
                $query = $item->insertQuery($table);
                $stmt = $this->db->prepare(
                    $query->getQuery(),
                    $query->getArgs()
                );
                $stmt->execute();
            } catch (PDOException $e) {
                $this->db
                     ->rollBack();
                throw new ExecutorException(
                    "Encounterd error executing insert. Rolling back previous inserts.", 
                    previous:$e
                );
            }
        }
        $this->db->commit();
    }

    /**
     * @throws ExecutorException
     */
    public function truncate(string $table): void {
        $result = $this->db
                       ->prepare("TRUNCATE TABLE ?", [$table])
                       ->execute();
        if (!$result) {
            throw new ExecutorException("Failed to truncate $table");
        }
    }

    /**
     * @throws ExecutorException
     */
    public function execScript(string $source): int { 
        $sql = file_get_contents($source);
        if (!$sql) {
            throw new ExecutorException("Failed to read script.");
        }
        $result = $this->db->exec($sql);
        if (!$result) {
            throw new ExecutorException("Script execution failed.");
        }
        return $result;
    }
}
