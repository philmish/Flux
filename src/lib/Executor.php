<?php declare(strict_types=1);

namespace Flux\lib;

use PDO;
use Flux\lib\error\ExecutorException;
use Flux\lib\error\SchemaException;
use PDOException;

abstract class Executor {
    protected PDO $db;

    protected function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * @throws ExecutorException
     *
     * @param string $dsn Data Source Name for Database
     *
     * @return Executor Initialized Executor
     */
    abstract static public function init(string $dsn): Executor;

    /**
     * @throws ExecutorException
     *
     * @param DataCollection
     *
     * @return int Returns the amount of inserted rows as an int
     */
    public function feed(DataCollection $data): int {
        try {
            $tr = $this->db->beginTransaction();
        } catch (PDOException $e) {
            throw new ExecutorException("Failed to start Db transaction.", previous:$e);
        }
        if (!$tr) {
            throw new ExecutorException("No transaction was initialized.");
        }
        $writtenRows = 0;
        foreach ($data->data() as $item) {
            if (!$item instanceof Data) {
                $this->db->rollBack();
                throw new ExecutorException(
                    "Unexpected data type. Rolling back previous inserts."
                );
            }
            try {
                $query = $item->insertQuery($data->table());
                $stmt = $this->db->prepare(
                    $query->getQuery(),
                );
                $stmt->execute($query->getArgs());
                $writtenRows += 1;
            } catch (PDOException $e) {
                $this->db
                     ->rollBack();
                throw new ExecutorException(
                    "Encounterd error executing inserting " . $query->getQuery() . " with values " . implode(", ", $query->getArgs()) . ". Rolling back previous inserts.", 
                    previous:$e
                );
            }
        }
        $this->db->commit();
        return $writtenRows;
    }

    /**
     * @throws ExecutorException
     *
     * @param string Name of the table to truncate
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
     *
     * @param string $source Path to script to run
     *
     * @return int Amount of affected rows
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

    /**
     * @throws ExecutorException
     *
     * @param string $tableName Name of the table to count rows in
     *
     * @return int Amount of rows in specified table
     */
    public function countTableRows(string $tableName): int {
        $query = "SELECT COUNT(*) as row_count FROM $tableName";
        try {
            $result = $this->db->query($query)->fetchAll();
        } catch (PDOException $e) {
            throw new ExecutorException(
                "Database exception while counting rows of table $tableName",
                previous:$e
            );
        }
        if (!$result || !array_key_exists("row_count", $result[0])) {
            throw new ExecutorException("No result from counting rows of table $tableName");
        }
        return $result[0]['row_count'];
    }

    /**
     * @throws ExecutorException
     *
     * @param string $tableName Name of the table to get the Schema of
     *
     * @return Schema
     *
     */
    public function getTableSchema(string $tableName): Schema {
        // TODO Implement fetching native type from fields
        $query = "SELECT * FROM $tableName LIMIT 1;";
        try {
            $result = $this->db->query($query)->fetch(); 
        } catch (PDOException $e) {
            throw new ExecutorException(
                "Failed to read entry from $tableName to determine Schema.",
                previous:$e
            );
        }
        if (!$result || count($result) == 0) {
            throw new ExecutorException(
                "No result from querying $tableName to determine Schema."
            );
        }
        $fields = [];
        foreach($result as $k => $v) {
            $d = ["name" => $k, "type" => gettype($v)];
            $fields[] = $d;
        }
        $data = [
            "table" => $tableName,
            "fields" => $fields,
        ];
        try {
            return Schema::fromArray($data);
        } catch (SchemaException $e) {
            throw new ExecutorException(
                "Failed to initialize Schema for $tableName from " . implode(", ", $data["fields"]),
                previous:$e
            );
        }
    }
}
