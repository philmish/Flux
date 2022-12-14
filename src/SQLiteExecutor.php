<?php declare(strict_types=1);

namespace Flux;

use Flux\Lib\Executor;
use PDO;
use Flux\lib\error\ExecutorException;
use PDOException;

final class SQLiteExecutor extends Executor {
    
    static public function init(string $dsn): Executor {
        try {
            $pdo = new PDO("sqlite:" . $dsn) ;
            $pdo->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );
            $pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            throw new ExecutorException("Failed to connect to SQLite DB.", previous:$e);
        }
       return new self($pdo);
    }

    /**
     * @throws ExecutorException
     */
    public function truncate(string $table): void {
        $result = $this->db->prepare("DELETE FROM $table")->execute();
        if (!$result) {
            throw new ExecutorException("Failed to truncate $table");
        }
    }
}
